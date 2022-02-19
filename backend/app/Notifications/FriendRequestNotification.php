<?php

namespace App\Notifications;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FriendRequestNotification extends Notification
{
    use Queueable;

    private FriendRequest $friendRequest;
    private User $user;

    /**
     * モデルの依存性注入
     *
     * @return void
     */
    public function __construct(FriendRequest $friendRequest, User $user)
    {
        $this->friendRequest = $friendRequest;
        $this->user = $user;
    }

    /**
     * 通知のチャンネルを指定
     * database:DB | mail:メール | broadcast:SMSとか？ | slack:slack
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * 通知に使用したいデータ
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {

        return [
            'created_at' => $this->friendRequest->created_at,
            'title' => "{$this->user->name}さんからフレンド申請が届いています。",
            'message' => $this->friendRequest->message,
        ];
    }
}
