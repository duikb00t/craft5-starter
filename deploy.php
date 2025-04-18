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
set('allow_anonymous_stats', false);

// Hosts
host('staging')
    ->setHostname('ssh101.webhosting.be')
    ->set('remote_user', 'imprentabe')
    ->set('deploy_path', '~/subsites/staging.imprenta.be');

// Tasks
desc('Install PHP dependencies');
task('deploy:vendors', function () {
    run('cd {{release_path}} && composer install --no-dev --quiet --prefer-dist --optimize-autoloader');
});

desc('Craft CMS: post-composer cleanup');
task('craft:post-install', function () {
    run('cd {{release_path}} && php craft clear-caches/all');
    run('cd {{release_path}} && php craft up');
});

desc('Install and build frontend assets');
task('deploy:assets', function () {
    run('cd {{release_path}} && npm install --legacy-peer-deps');
    run('cd {{release_path}} && npm run build');
    run('cd {{release_path}} && rm -rf node_modules');
});

desc('Craft CMS: apply all pending migrations');
task('craft:migrate', function () {
    run('cd {{release_path}} && php craft migrate/all');
});

desc('Craft CMS: sync project config');
task('craft:project-config-sync', function () {
    run('cd {{release_path}} && php craft project-config/sync --force');
});

desc('Craft CMS: clear Craft caches again');
task('craft:clear-caches', function () {
    run('cd {{release_path}} && php craft cache/flush-all');
});

// Hooks
after('deploy:shared', 'deploy:vendors');
after('deploy:vendors', 'craft:post-install');
after('craft:post-install', 'deploy:assets');
after('deploy:assets', 'craft:migrate');
after('craft:migrate', 'craft:project-config-sync');
after('craft:project-config-sync', 'craft:clear-caches');
after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'craft:clear-caches');
