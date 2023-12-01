<?php
namespace Deployer;

require 'recipe/symfony.php';
require 'contrib/yarn.php';


// Project name
set('application', 'my_web_app');

// Project repository
set('repository', 'git@github.com:cecilie182/my_web_app.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);
set('ssh_multiplexing', false);


// Shared files/dirs between deploys 
add('shared_dirs', ['var/log', 'var/sessions', 'public/uploads']);
add('writable_dirs', ['var', 'public/uploads']);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);


set('env', function () {
    return [
        'APP_ENV' => 'prod',
    ];
});

// Hosts

host('35.162.172.2') // 54.68.153.66
    ->setRemoteUser('bitnami')
    ->setIdentityFile('~/.ssh/LightsailDefaultKey-us-west-2.pem')
    ->set('labels', ['stage' => 'production'])
    ->setForwardAgent(true)
    ->set('deploy_path', '~/{{application}}');


// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

task('yarn:run', function () {
    run('cd {{release_path}} && NODE_OPTIONS=--openssl-legacy-provider yarn run encore production');
});

before('deploy:symlink', 'yarn:install');
// after('yarn:install', 'yarn:run');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


// Migrate database before symlink new release.
//before('deploy:symlink', 'database:migrate');

