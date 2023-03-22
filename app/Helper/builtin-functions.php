<?php
use App\Models\User;

function isAdmin()
{
    return User::isAdmin();
}


//快速修改.env文件
function modifyEnv(array $data)
{
    $envPath      = base_path() . DIRECTORY_SEPARATOR . '.env';
    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
    $contentArray->transform(function ($item) use ($data)
    {
        foreach ($data as $key => $value) {
            if (str_contains($item, $key)) {
                return $key . '=' . $value;
            }
        }
        return $item;
    });
    $content = implode($contentArray->toArray(), "\n");
    \Illuminate\Support\Facades\File::put($envPath, $content);
}

// 获取数据表的前缀
function get_db_prefix()
{
    return config('database.connections.' . config('database.default') . '.prefix');
}

function setting($name, $default = '')
{
    $all_settings = \App\Models\Setting::getAllSettings();
    // var_dump($all_settings);
    return $all_settings->get($name) ?? $default;
}
