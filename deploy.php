<?php
namespace Deployer;

require 'recipe/common.php';

// SSH Config
set('http_user', 'imprentabe');
set('ssh-type', 'native');
set('ssh_multiplexing', true);

// Config
set('application', 'craftcms5');
set('repository', 'git@github.com:duikb00t/craft5-starter.git');
set('git_tty', true);
set('keep_releases', 4);
set('shared_files', ['.env']);
set('shared_dirs', ['storage']);
//set('writable_dirs', ['storage', 'web/cpresources']);
set('allow_anonymous_stats', false);

// Hosts
host('staging')
    ->setHostname('ssh101.webhosting.be')
    ->set('remote_user', 'imprentabe')
    ->set('deploy_path', ' ~/subsites/staging.imprenta.be');

// Tasks
desc('Install dependencies');
task('deploy:vendors', function () {
    run('cd {{release_path}} && composer install --no-dev --quiet --prefer-dist --optimize-autoloader');
});

desc('Craft CMS: run project config sync');
task('craft:project-config-sync', function () {
    run('cd {{release_path}} && php craft project-config/sync --force');
});

desc('Craft CMS: clear caches');
task('craft:clear-caches', function () {
    run('cd {{release_path}} && php craft cache/flush-all');
});

desc('Craft CMS: apply all pending migrations');
task('craft:migrate', function () {
    run('cd {{release_path}} && php craft migrate/all');
});

// Hooks
after('deploy:shared', 'deploy:vendors');
after('deploy:vendors', 'craft:migrate');
after('craft:migrate', 'craft:project-config-sync');
after('craft:project-config-sync', 'craft:clear-caches');
after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'craft:clear-caches');
