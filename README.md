# [Access](../../)

#### <b>Access</b> is a simple scalable WordPress plugin to control user access via the [taxonomy API](http://codex.wordpress.org/Taxonomies) and user [roles/capabilities](http://codex.wordpress.org/Roles_and_Capabilities)/IDs.

## Usage

Create access terms via <b>Posts</b> &rarr; <b>Access</b> &rarr; <b>Add New</b>. Choose any <b>name</b> and set its <b>slug</b> to a [role](http://codex.wordpress.org/Roles_and_Capabilities#Roles), [capability](http://codex.wordpress.org/Roles_and_Capabilities#Capabilities), or user ID. 

#### Example Terms

<table>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Slug</th>
        <th scope="col">Restricts By</th>
    </tr>
    <tr>
        <td><kbd>Members</kbd></td>
        <td><kbd>read</kbd></td>
        <td>capability</td>
    </tr>
    <tr>
        <td><kbd>Editors</kbd></td>
        <td><kbd>editor</kbd></td>
        <td>role</td>
    </tr>
    <tr>
        <td><kbd>John Doe</kbd></td>
        <td><kbd>47</kbd></td>
        <td>user ID</td>
    </tr>
</table>

Access terms can be added to any post type via <b>Edit</b> (or <b>Quick Edit</b>) similar to how categories are added. Posts with access terms are only seen by users logged in with sufficient capability. Denied posts are excluded from the loop.

## UX

### Contexts

- Viewing an access-controlled <b>singular</b> item. (Its [permalink](http://en.wikipedia.org/wiki/Permalink).)
- Viewing a <b>collection</b> that includes (all or some) access-controlled items. In this case only items that the current user has permission to view are displayed.

### Customization

Use a hook to display a [login form](http://codex.wordpress.org/Function_Reference/wp_login_form) and/or message to inform users that they can log in to access more content.

#### Hooks

##### Filter hooks during the `'loop_start'` action 

- `'@access:message'` runs for all cases
  - `'@access:message:!denied'` runs if all posts are <b>not</b> denied
  - `'@access:message:!limited'` runs if posts are all granted <b>or</b> all denied
  - `'@access:message:!granted'` runs if all posts are <b>not</b> granted
  - `'@access:message:denied'` runs if all posts are denied
  - `'@access:message:limited'` runs if some posts are granted, some denied
  - `'@access:message:granted'` runs if all posts are granted

##### Example Message

```php
add_filter('@access:message', function($message, $grants, $denies) {
    if (is_user_logged_in()) return;
    $url = admin_url();
    return "<a href='$url'>Login</a> to view additional content.";
}, 10, 3);
```

##### Example Login Form 

```php
add_filter('@access:message', function($message, $grants, $denies) {
    if (is_user_logged_in()) return;
    $form = "<h3>Login</h3>" . wp_login_form(array('echo' => 0));
    return "<div class='loop-login'>$form</div>";
}, 10, 3);
```

#### CSS

For CSS purposes, messages are wrapped in a `div.access-message` with an applicable contextual class.

## Install

<b>Requires:</b> PHP 5.3+

1. Upload to the `/wp-content/plugins/` directory
1. Activate through the Plugins menu in WordPress

## Fund

Fund development with [tips to @ryanve](https://www.gittip.com/ryanve/) =)

## License: [MIT](http://opensource.org/licenses/MIT)

Copyright (C) 2013 by [Ryan Van Etten](https://github.com/ryanve)