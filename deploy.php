<?php
namespace Deployer;

require 'recipe/common.php';

// Config
set('application', 'craftcms5');
set('repository', 'git@github.com:duikb00t/craft5-starter.git');
set('git_tty', true);
set('keep_releases', 5);
set('shared_files', ['.env']);
set('shared_dirs', ['storage']);
set('writable_dirs', ['storage', 'web/cpresources']);
set('allow_anonymous_stats', false);

// Hosts
host('production')
    ->setHostname('yourserver.com')
    ->set('remote_user', 'deployuser')
    ->set('deploy_path', '/var/www/craftcms5');

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
