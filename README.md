Install
---

1. Enable changes_features and chagnes modules
2. Run `drush atr` to make sure dependencies are downloaded
3. Clone Drupal repository to `/var/aegir/platforms/d8dev`:
    - `mkdir -p /var/aegir/platforms`
    - `git clone --branch=8.x https://github.com/drupal/drupal.git d8dev`
4. Change owner of /var/aegir/platforms/d8dev to cron runner, config cronjob for
    Drupal to auto import new changes.
5. Run Drush command: `drush changes_import`
6. Commits are now available at: /commits

Todo
---

1. Import `Change notices`
1. Import contributors
1. Import commit files


Screenshot
---

![](http://farm4.staticflickr.com/3744/12297266705_b875f65275_z.jpg)
