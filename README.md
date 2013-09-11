# Access

[Access](../../) is a simple scalable WordPress plugin to control user access via the [taxonomy API](http://codex.wordpress.org/Taxonomies) and user [roles/capabilities](http://codex.wordpress.org/Roles_and_Capabilities)/IDs.

## Usage

### Setup

Create access terms via <b>Posts</b> &rarr; <b>Access</b> &rarr; <b>Add New</b>. Choose any <b>name</b> and set its <b>slug</b> to a [role](http://codex.wordpress.org/Roles_and_Capabilities#Roles), [capability](http://codex.wordpress.org/Roles_and_Capabilities#Capabilities), or user ID. 

#### Example Terms

<table>
    <tr><th scope="col">Name</th><th scope="col">Slug</th><th scope="col">Restricts By</th></tr>
    <tr><td><kbd>Members</kbd></td><td><kbd>read</kbd></th><td>capability</td></tr>
    <tr><td><kbd>Editors</kbd></td><td><kbd>editor</kbd></th><td>role</td></tr>
    <tr><td><kbd>John Doe</kbd></td><td><kbd>47</kbd></th><td>user ID</td></tr>
</table>

Access terms can be added to any post type via <b>Edit</b> (or <b>Quick Edit</b>) similar to how categories are added. Posts with access terms are only seen by users logged in with sufficient capability.

### UX

#### Contexts

- Viewing an access-controlled <b>singular</b> item. (Its [permalink](http://en.wikipedia.org/wiki/Permalink).)
- Viewing a <b>collection</b> that includes (all or some) access-controlled items. In this case only items that the current user has permission to view are displayed.

#### Customization

Hook `'@access:loop_start'` to display a [login form](http://codex.wordpress.org/Function_Reference/wp_login_form) and/or message to inform users that they can log in to access more content.

##### Example Message

```php
add_filter('@access:loop_start', function($arr) {
    if (is_user_logged_in()) return;
    $url = admin_url();
    $tab = \str_repeat(' ', 16);
    $msg = "<a href='$url'>Login</a> to view additional content.";
    return "\n$tab<div class='loop-access'>$msg</div>\n";
});
```

##### Example Login Form 

```php
add_filter('@access:loop_start', function($arr) {
    if (is_user_logged_in()) return;
    $tab = \str_repeat(' ', 16);
    $form = "<h3>Login</h3>" . wp_login_form(array('echo' => 0));
    return "\n$tab<div class='loop-access loop-login'>$form</div>\n";
});
```

## Install

<b>Requires:</b> PHP 5.3+

1. Upload to the `/wp-content/plugins/` directory
1. Activate through the Plugins menu in WordPress

## License: [MIT](http://opensource.org/licenses/MIT)

Copyright (C) 2013 by [Ryan Van Etten](https://github.com/ryanve)