<?php
namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Newsinfo;
use App\Models\Pageadver;
use App\Models\Profile;
use App\Models\Qiniutask;
use App\Models\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use zgldh\QiniuStorage\QiniuStorage;

class QiniuUploadFile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $task;

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct(Qiniutask $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $task = $this->task;
        $table_name = $task->table_name;
        $field_name = $task->field_name;
        $master_id = $task->master_id;

        $file = $task->file_path;
        $key = str_replace([public_path() . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], ['', '/'], $file);
        if (file_exists($file) == false) {
            return false;
        }

        $contents = file_get_contents($file);
        $disk = QiniuStorage::disk('qiniu');
        if ($disk->put($key, $contents)) {
            $url = $disk->downloadUrl($key);
            $task->target_path = $url;

            switch ($table_name) {
                case 'users':
                    $item = User::find($master_id);
                    break;
                case 'profiles':
                    $item = Profile::find($master_id);
                    break;
                case 'news_info':
                    $item = Newsinfo::find($master_id);
                    break;
                case 'pageadver':
                    $item = Pageadver::find($master_id);
                    break;
                default:
                    break;
            }

            if (!empty($item)) {
                $item->$field_name = $url;
                if ($item->save()) {
                    $task->status = 3;
                    $task->save();
                }
            }
        }
        return true;
    }
}
