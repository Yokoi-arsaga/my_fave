<?php

namespace App\Modules;

use Illuminate\Support\Facades\Log;

class ApplicationLogger{
    /**
     * ログレベル
     * @var array
     */
    private const LEVEL = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    /**
     * ログプレフィックス
     * @var string
     */
    private $prefix = '';

    /**
     * 処理開始時間(loggerがnewされた時間)
     * @var integer
     */
    private $start = 0;

    /**
     * 正常|異常判定フラグ
     *
     * @var boolean
     */
    private $status = false;

    /**
     * @param string $className
     * @param boolean $isStartLog
     */
    public function __construct(string $className, bool $isStartLog = true)
    {
        $this->prefix = "[{$className}] ";
        $this->start = microtime(true);

        if ($isStartLog) $this->start();
    }

    /**
     * destruct
     */
    public function __destruct()
    {
        $time = microtime(true) - $this->start;
        $status = $this->status ? '(正常終了)' : '(異常終了)';
        Log::info("{$this->prefix}[END] status: {$status} 処理時間: {$time}秒");
    }

    /**
     * statusをtrueに更新
     * @return void
     */
    public function success(): void
    {
        $this->status = true;
    }

    /**
     * logPrefixを変更する
     * @param string $prefix
     * @return void
     */
    public function changePrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * 開始ログを出力する(__constructで出力しない場合に使用してください)
     * @return void
     */
    public function start(): void
    {
        Log::info("{$this->prefix}[START]");
    }

    /**
     * 定数LEVELに定義しているログレベルに応じでログ出力を行う
     * 第一引数はmessage
     * 第二引数はlevel(デフォルトでinfo)
     * 第三引数は処理時間(startからの経過時間)を出力するかどうかのフラグ
     * @param string $message
     * @param string $level
     * @param boolean $t
     * @return void
     */
    public function write($message, $level = 'info', $t = false): void
    {
        $time = '';
        if ($t === true) {
            $t = microtime(true) - $this->start;
            $time = " : {$t}秒";
        }

        if(in_array($level, self::LEVEL)) {
            Log::$level($this->prefix.$message.$time);
        }
    }

    /**
     * エラーの出力
     * @param \Throwable $e
     * @return void
     */
    public function exception(\Throwable $e): void
    {
        $this->write(
            $e->getMessage() . ' FILE:' . $e->getFile() . ' LINE:' . $e->getLine() . ' ERROR:' . $e->getTraceAsString(),
            'error'
        );
    }
}
