@servers(['localhost' => '127.0.0.1'])

@include('vendor/autoload.php')

@setup
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    $bucket = 'cdn.manivelle.io';
    $profile = getenv('AWS_PROFILE');
    if (empty($profile)) {
        $profile = 'flklr@manivelle';
    }

    $status = shell_exec('eb status');
    preg_match('/Application name\: ([^\s]+)/', $status, $matches);
    $appName = $matches[1];
    preg_match('/Environment details for\: ([^\s]+)/', $status, $matches);
    $envName = $matches[1];
    $gitSha = preg_replace('/[\r\t\n]/', '', shell_exec('git rev-parse --short HEAD'));
    $date = date('Ymd-Hi');
    $version = $envName.'-'.$date.'-'.$gitSha;

    $paths = array_map("escapeshellarg", [
        './artisan',
        './composer.json',
        './package.json',
        './composer.lock',
        './.ebextensions',
        './.env.elasticbeanstalk',
        './cron.yaml',
        './app',
        './bootstrap',
        './config',
        './database',
        './public',
        './resources',
        './vendor',
        './storage'
    ]);

    $exclude = array_map("escapeshellarg", [
        '*.git*',
        './vendor/panneau/bubbles/.git/*',
        './vendor/panneau/panneau/.git/*',
        './bootstrap/cache/*',
        './public/files/*',
        './resources/assets',
        './storage/app/*',
        './storage/debugbar/*',
        './storage/framework/cache/*',
        './storage/framework/sessions/*',
        './storage/framework/views/*',
        './storage/logs/*'
    ]);

    $pathOptions = implode(' ', $paths);
    $excludeOptions = '! -path '.implode(' ! -path ', $exclude);

    $zipPath = './.elasticbeanstalk/build/'.$version.'.zip';

@endsetup

@task('deploy')

    echo "Creating zip file for version {{ $version }}..."
    find {{ $pathOptions }} {{ $excludeOptions }} -print | zip "{{ $zipPath }}" -q -@
    echo "Zip file created."

    echo "Copying {{ $zipPath }} to S3..."
    aws s3 cp "{{ $zipPath }}" "s3://{{ $bucket }}/build/" --profile {{ $profile }}
    echo "File copied."

    echo "Creating elastic beanstalk application version {{ $version }}..."
    aws elasticbeanstalk create-application-version --application-name {{ $appName }} --version-label {{ $version }} --source-bundle S3Bucket="{{ $bucket }}",S3Key="build/{{ $version }}.zip" --profile {{ $profile }}
    echo "Application version {{ $version }} created."

    echo "Deploying application version {{ $version }}..."
    eb deploy --version {{ $version }}
    echo "Application version {{ $version }} deployed."

@endtask
