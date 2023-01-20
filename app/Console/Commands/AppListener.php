<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Components\Console\Console;
use App\Components\Logger\LoggerTrait;
use App\Components\PostponedActions\ActionInterface;
use App\Components\PostponedActions\FetchActivitiesAction;
use App\Components\PostponedActions\ReportAdminAction;
use App\Components\RabbitMq\RabbitMqService;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Consumer;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Throwable;

class AppListener extends Command
{
    use LoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature        = 'queue:listen-activities';

    public const ACTION_REPORT          = 'send-report';
    public const ACTION_FETCH_ACTIVITY  = 'download-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen activities and send to admin on changes';

    public array $actionsList = [
        self::ACTION_REPORT         => ReportAdminAction::class,
        self::ACTION_FETCH_ACTIVITY => FetchActivitiesAction::class
    ];

    public function doActionManager(LoggerInterface $logger, $data)
    {
        $action     = $data['action'];
        $className  = $this->actionsList[$action] ?? null;
        if (!$className) {
            $this->log($logger, "Unknown action $action", Console::COLOR_RED);
            return;
        }

        /** @var ActionInterface $actionObject */
        $actionObject = new $className();
        $actionObject->do($logger, $data);
    }

    /**
     * Execute the console command.
     *
     * @param Amqp $amqp
     * @param LoggerInterface $logger
     * @return bool
     * @throws Configuration
     */
    public function handle(Amqp $amqp, LoggerInterface $logger): bool
    {
        $this->log($logger, 'Listening for messages...', Console::COLOR_GREEN);

        RabbitMqService::listen($amqp, function (AMQPMessage $message, Consumer $consumer) use ($logger) {
            try {
                $data = json_decode($message->body, true);
                $this->doActionManager($logger, $data);

                $consumer->acknowledge($message);
            }
            catch (Throwable $throwable) {
                $this->log($logger, 'Message error: ' . $throwable->getMessage(), Console::COLOR_RED);
                $consumer->reject($message);
            }
        });

        return self::SUCCESS;
    }
}
