<?php

namespace App\Notifications;

use App\Facades\NotificationService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as LaravelNotification;
use Illuminate\Queue\SerializesModels;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Inline\Text;
use League\HTMLToMarkdown\HtmlConverter;
use NotificationChannels\Telegram\TelegramBase;
use NotificationChannels\Telegram\TelegramMessage;

abstract class Notification extends LaravelNotification
{
    use SerializesModels;

    public static bool $userSetting = true;
    private array $overrideVia = [];

    public function via(object $notifiable): array {
        return NotificationService::channels($this, $notifiable, $this->getVia());
    }

    public function toMail(object $notifiable): Renderable {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->lines($this->getLines())
            ->action($this->getAction(), $this->getActionUrl());
    }

    public function toTelegram(object $notifiable): TelegramBase {
        return tap(TelegramMessage::create(), function(TelegramMessage $message) {
            if($this->getSubject())
               $message->line($this->cleanMarkdown("*" . $this->getSubject() . "*"))
                       ->line("");
            $message->line($this->cleanMarkdown(collect($this->getLines())->join("\n")));
            if($this->getAction())
                $message->button($this->cleanMarkdown($this->getAction()), $this->getActionUrl());
        });
    }


    /**
     * we have to use this annoying getter construct in order to apply the correct localization on each $notifiable
     */

    protected function getSubject(): ?string { return null; }
    protected function getLines(): array { return []; }

    protected function getAction(): ?string { return null; }

    protected function getActionUrl(): string { return ""; }

    public static function userSetting(): bool {
        return self::$userSetting;
    }

    /**
     * overrides the "additional channels" on "via"
     * Only use to "enforce" specific channels!
     */
    protected function getVia(): array { return $this->overrideVia; }

    public function setVia(array $via): void {
        $this->overrideVia = $via;
    }

    public static function getName(): string {
        $data = preg_split('/(?=[A-Z])/', basename(static::class));
        $string = trim(implode(' ', $data));
        return __(ucwords($string));
    }

    /**
     * storing data for database-notifications
     */
    public function toArray(object $notifiable): array {
        return [
            'subject'       => $this->getSubject(),
            'lines'         => $this->getLines(),
            'action'        => $this->getAction(),
            'action_url'    => $this->getActionUrl(),
        ];
    }

    public static function view($data) {
        return view("notifications.type.default", $data);
    }

    public function getMorphName(): string {
        return NotificationService::morphName(static::class);
    }

    /**
     * parses markdown and escapes invalid tags
     *
     * @param $content
     * @return string
     */
    public function cleanMarkdown($content): string {
        // adding white spaces for correct line breaks
        $content = str_replace("\n", "  \n", $content);

        $env = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
        $env->addExtension(new CommonMarkCoreExtension());

        // 2) Nach dem Parsen alle *unescaped* einzelnen * oder _ in Text-Knoten entfernen
        $env->addEventListener(DocumentParsedEvent::class, function (DocumentParsedEvent $e) {
            $walker = $e->getDocument()->walker();
            while ($event = $walker->next()) {
                if (!$event->isEntering()) continue;
                $node = $event->getNode();
                if ($node instanceof Text) {
                    $s = $node->getLiteral();

                    // Variante 2 (robust):
                    $s = preg_replace('/(?<!\\\\)(?:\\\\\\\\)*\K([*_])/', "\$1", $s);

                    $node->setLiteral($s);
                }
            }
        });

        $converter = new MarkdownConverter($env);

        try {
            // 3) Markdown -> HTML (sanitized)
            $html = (string)$converter->convert($content);
            // 4) HTML -> Markdown
            $toMd = new HtmlConverter([
                'remove_nodes' => 'script,style',
            ]);

            return htmlspecialchars_decode($toMd->convert($html));
        } catch(CommonMarkException $e) {
            $search  = [
                '_', '*', '[', ']', '(', ')', '~', '`', '>', '#',
                '+', '-', '=', '|', '{', '}', '.', '!'
            ];
            $replace = array_map(fn($c) => '\\' . $c, $search);

            return str_replace($search, $replace, $content);
        }
    }

}
