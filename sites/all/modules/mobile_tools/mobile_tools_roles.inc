<?php
/**
 * Being called in the hook_init() implementation
 * This function is in charge of changing the user role
 */
function mobile_tools_roles_boot() {
  global $user;
  $roles = $user->roles;
  //count the number of mobile roles... must be bigger then 1
  $item = db_fetch_object(db_query("SELECT COUNT(*) as count FROM {mobile_tools_roles_relations}"));
  if ($item->count > 0) {
    foreach ($roles as $key => $value) {
      $role = mobile_tools_roles_info(array('id' => $key));
      if ($role->type == 'desktop' && $role->has_sibling == 1 && ($_SESSION['mobile-tools-site-type'] == 'mobile' || ($_SESSION['mobile-tools-mobile-device']['type'] == 'mobile' && variable_get('mobile_tools_theme_switch', FALSE) == 'mobile-tools-mobile-device'))) {
        unset($user->roles[$key]);
        $user->roles[$role->sibling['id']] = $role->sibling['name'];
      }
    }
  }
}

/**
 *  Implementation of the user_load function to assure that the right role is being assigned to the user.
 *  This is the same actions as in the hook_init() method
 */
function mobile_tools_user_OLD($op, &$edit, &$account, $category = NULL) {
  // TODO Remaining code in this function needs to be moved to the appropriate new hook function.
  if (variable_get('mobile_tools_enable_roles', 0)) {
    switch ($op) {
      case 'load':
        $user = $account;
        $roles = $user->roles;
        foreach ($roles as $key => $value) {
          $role = mobile_tools_roles_info(array('id' => $key));
          if ($role->type == 'desktop' && $role->has_sibling == 1 && $_SESSION['mobile-tools-site-type'] == 'mobile') {
            unset($user->roles[$key]);
            $user->roles[$role->sibling['id']] = $role->sibling['name'];
          }
        }
        break;
    }
  }
}
/**
 * @file
 * Contains the functionality to add mobile user roles
 *
 */

/**
 * Configuration form for configuring the mobile context in the permission system
 */
function mobile_tools_roles_configuration_form($form, &$form_state) {
  global $base_url;
  $form['mobile_tools_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mobile Tools settings'),
    '#collapsible' => TRUE,
    '#description' => t('The Mobile Tools module allows the creation of a mobile version of each role . these mobile versions of each role will be assigned to the mobile user. In the !url configuration panel, you can assign permissions to the mobile user.', array('!url' => l('permissions', $base_url . '/admin/user/permissions'))),
  );
  $form['mobile_tools_settings']['mobile_tools_enable_roles'] = array(
    '#type' => 'checkbox',
    '#title' => t('Activate mobile user roles' ),
    '#description' => t('When activated, mobile users will get the mobile versions of their normal roles when the site is being mobilized.'),
    '#default_value' => variable_get('mobile_tools_enable_roles', 0),
  );

  $form['mobile_tools_roles'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mobile roles'),
    '#collapsible' => TRUE,
    '#description' => t('Enable or disable the mobile version of each user role. When no mobile role is created, the user will
    keep its normal role. The settings can also be configured in the !roles configuration section.', array('!roles' => l('roles', 'admin/user/roles'))),
    '#suffix' => mobile_tools_roles_overview(),
  );

  $result = db_query("SELECT * FROM {role}");
  foreach ($result as $item) {
    $role = mobile_tools_roles_info(array('id' => $item->rid));
    if ($role->type == 'desktop') {
      $form['mobile_tools_roles']['mobile_tools_role_' . $item->rid] = array(
        '#type' => 'checkbox',
        '#title' => $item->name,
        '#default_value' => ($role->has_sibling) ? TRUE : FALSE,
        '#description' => t('Enabling will create the %role role . the name can be changed afterwards in the !roles settings page', array('%role' => $role->name . ' (Mobile)', '!role' => l('roless', 'admin/user/roles'))),
      );
    }
  }
  $form['#submit'][] = 'mobile_tools_roles_settings_submit';
  return system_settings_form($form);
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function mobile_tools_roles_overview() {
  $output = '';
  $query = "SELECT * FROM {mobile_tools_roles_relations}";
  $result = db_query($query);
  $rows = array();
  foreach ($result as $item) {
    $query = "SELECT * FROM {role} WHERE rid = %d";
    $result1 = db_query("SELECT * FROM {role} WHERE rid = :rid", array(':rid' => $item->rid));
    $result2 = db_query("SELECT * FROM {role} WHERE rid = :rid", array(':rid' => $item->mrid));
    $rows[] = array(db_fetch_object($result1)->name, db_fetch_object($result2)->name);
  }
  $headers = array("original role", "mobile role");
  if (count($rows) == 0) {
    $output .= '<div class="message">No Mobile roles were assigned</div>';
  }
  else {
    $output .= theme('table', array('header' => $headers, 'rows' => $rows));
    $output .= '<br>' . t('Configure the !permissions', array('!permissions' => l('permissions', 'admin/user/permissions'))) . '<br />';
  }
  return $output;
}
/**
 * Helper function to return the desktop user roles
 */
function mobile_tools_user_roles() {
  //$roles = array('none');
  $roles = user_roles();
  $desktop_roles = array();
  foreach ($roles as $key => $value) {
    $role = mobile_tools_roles_info(array('id' => $key));
    if ($role->type == 'desktop') {
      $desktop_roles[$key] = $value;
    }
  }
  return $desktop_roles;
}

/**
 * Helper function to return the mobile user roles
 */
function mobile_tools_mobile_user_roles() {
  //$roles = array('none');
  $roles = user_roles();
  $mobile_roles = array();
  foreach ($roles as $key => $value) {
    $role = mobile_tools_roles_info(array('id' => $key));
    if ($role->type == 'mobile') {
      $mobile_roles[$key] = $value;
    }
  }
  return $mobile_roles;
}
/**
 * Function helping in getting information for each role:
 * @param $identifier
 *  array('id' => id) or array('name' => name)
 *
 * @return
 *    a $role object with the folowing info
 *  $role->id
 * $role->name
 * $role->type = mobile/desktop
 * $role->sibling[id]
 * $role->sibling[name]
 */
function mobile_tools_roles_info($identifier) {
  if (array_key_exists('id', $identifier)) {
    $query = "SELECT * FROM {role} WHERE rid = %d";
    $result = db_query("SELECT * FROM {role} WHERE rid = :rid", array(':rid' => $identifier['id']));
    $record = $result->fetchObject();
    $name = $record->name;
    $rid = $identifier['id'];
  }
  elseif (array_key_exists('name', $identifier)) {
    $query = "SELECT * FROM {role} WHERE name = '%s'";
    $result = db_query("SELECT * FROM {role} WHERE name = :name", array(':name' => $identifier['name']));
    $record = $result->fetchObject();
    $rid = $record->rid;
    $name = $identifier['name'];
  }
  $role->id = $rid;
  $role->name = $name;
  $query = "SELECT COUNT(*) as count, mrid FROM {mobile_tools_roles_relations} WHERE rid = %d GROUP BY mrid";
  $result = db_query("SELECT COUNT(*) as count, mrid FROM {mobile_tools_roles_relations} WHERE rid = :rid GROUP BY mrid", array(':rid' => $rid));
  $item = $result->fetchObject();
  if (is_object($item)) {
    $count = $item->count;
  }
  else {
    $count = 0;
  }
  if ($count != 0) {
    $role->type = 'desktop';
    $role->has_sibling = 1;
    $result = db_query("SELECT * FROM {role} WHERE rid = :rid", array(':rid' => $item->mrid));
    $sibling = db_fetch_object($result);
  }
  else {
    $result = db_query("SELECT COUNT(*) as count, rid FROM {mobile_tools_roles_relations} WHERE mrid = :mrid GROUP BY rid", array(':mrid' => $rid));
    $item = $result->fetchObject();
    if ($count == 0) {
      $role->has_sibling = 0;
      $role->type = 'desktop';
    }
    else {
      $role->type = 'mobile';
      $role->has_sibling = 1;
      $result = db_query("SELECT * FROM {role} WHERE rid = :rid", array(':rid' => $item->rid));
      $sibling = $result->fetchObject();
    }
  }
  if (isset($sibling)) {
    $role->sibling['id'] = $sibling->rid;
    $role->sibling['name'] = $sibling->name;
  }

  return $role;
}

/**
 * Submit function for the mobile tools / mobile roles configuration page
 *
 */
function mobile_tools_roles_settings_submit($form) {
  $query = "SELECT * FROM {role}";
  $result = db_query($query);
  foreach ($result as $item) {
    $role = mobile_tools_roles_info(array('id' => $item->rid));
    if ($role->type == 'desktop') {
      $choice = $form['mobile_tools_roles']['mobile_tools_role_' . $item->rid]['#value'];
      if ($role->has_sibling == 1 && $choice == 0) { // DELETE
        mobile_tools_edit_mobile_role('delete', $role);
      }
      elseif ($role->has_sibling == 0 && $choice == 1) {
        mobile_tools_edit_mobile_role('add', $role);
      }
    }
  }
}

/**
 * Submit handler for the roles configuration form. It organises the mobile context for each user role.
 *
 */
function mobile_tools_roles_configuration_submit($form) {
  $role = mobile_tools_roles_info(array('id' => $form['rid']['#value']));
  switch ($form['#post']['op']) {
    case 'Save role':
      if ($role->has_sibling == 1 && $form['#post']['mobile_tools_configure_role_' . $form['rid']['#value']] == FALSE && $role->type == 'desktop') {
        mobile_tools_edit_mobile_role('delete', $role);
      }
      elseif ($role->has_sibbling == FALSE &&   $form['#post']['mobile_tools_configure_role_' . $form['rid']['#value']] == TRUE && $role->type == 'desktop') {
        mobile_tools_edit_mobile_role('add', $role);
      }
      break;
    case 'Delete role':
      if ($role->type == 'mobile') {
        $role = mobile_tools_roles_info(array('id' => $role->sibling['id']));
      }
      mobile_tools_edit_mobile_role('delete', $role);
  }
}

/**
 * Function helping in saving and deleting the mobile roles
 * @param $op
 *  the operation that has to be performed: 'delete' will delete the mobile role, 'add' will add the mobile role.
 * @param $role
 *  the $role object of the normal role (the already existing non-mobile role)
 */
function mobile_tools_edit_mobile_role($op, $role) {
  switch ($op) {
    case 'delete':
      // TODO Please review the conversion of this statement to the D7 database API syntax.
      /* db_query('DELETE FROM {role} WHERE rid = %d', $role->sibling['id']) */
      db_delete('role')
  ->condition('rid', $role->sibling['id'])
  ->execute();
      // TODO Please review the conversion of this statement to the D7 database API syntax.
      /* db_query('DELETE FROM {mobile_tools_roles_relations} WHERE mrid = %d', $role->sibling['id']) */
      db_delete('mobile_tools_roles_relations')
  ->condition('mrid', $role->sibling['id'])
  ->execute();
      break;
    case 'add':

      // TODO Please review the conversion of this statement to the D7 database API syntax.
      /* db_query("INSERT INTO {role} (name) VALUES ('%s')", $role->name . ' (Mobile)') */
      $result = $id = db_insert('role')
  ->fields(array(
    'name' => $role->name . ' (Mobile)',
  ))
  ->execute();
      // TODO Please review the conversion of this statement to the D7 database API syntax.
      /* db_query("INSERT INTO {mobile_tools_roles_relations} (rid, mrid) VALUES (%d, %d)", $role->id, db_last_insert_id('role', 'rid')) */
      $id = db_insert('mobile_tools_roles_relations')
  ->fields(array(
    'rid' => $role->id,
    'mrid' => db_last_insert_id('role', 'rid'),
  ))
  ->execute();
      break;
  }
}
