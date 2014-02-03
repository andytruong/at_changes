Install
---

1. Enable changes_features and chagnes modules
1. Clone Drupal repository to /var/aegir/platforms/d8dev:
    - `mkdir -p /var/aegir/platforms`
    - `git clone --branch=8.x https://github.com/drupal/drupal.git d8dev`
1. Run Drush command: `drush changes_import`
1. Commits are now available at: /commits

Todo
---

1. Import `Change notices`
1. Import contributors
1. Import commit files
