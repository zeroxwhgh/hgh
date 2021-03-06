<?php
/**
 * @file
 * gmap.strings.php
 * PURPOSE
 *
 * This is a stand-alone script which
 * takes markers titles and wraps them in t()'s.
 * The reason we want to do this is because variables
 * can not be passed through t() with .pot files generated
 * by the potx module.
 *
 * The result of running this script should be the creation
 * of a file in the root of the gmap module's directory, called
 * 'gmap.strings.inc', containing a function 'gmap_marker_labels'
 * which returns an array holding each of the  marker labels wrapped in t().
 *
 * INSTALLATION
 *
 * This script needs to be run within drupal, so the best way
 * to accomplish this is to install and enable the devel module, then,
 * enable the 'Execute PHP' block somewhere.
 *
 * Then, just paste this script into the text field in the Execute PHP block,
 * and click 'Execute'!
 * This will create the gmap.strings.inc file in the gmap module's directory -
 * be sure to adjust the permissions of this new file appropriately!
 */

$markerdir = variable_get('gmap_markerfiles', drupal_get_path('module', 'gmap') . '/markers');

// The following routines are designed to be easy to comprehend, not fast.
// This whole process gets cached.

// Get the ini files.
$inifiles = file_scan_directory($markerdir, '.*\.ini$');

$labels = array();
foreach ($inifiles as $file) {
  $icon_data = parse_ini_file($file->filename, TRUE);
  foreach ($icon_data as $icon_set) {
    if (isset($icon_set['name'])) {
      $marker_labels[] = "t('" . $icon_set['name'] . "'),";
    }
  }
}

// Create a .inc file for our info.
$gmap_strings_inc_name = drupal_get_path('module', 'gmap') . '/gmap.strings.inc';

// Open our file.
$gmap_strings_inc_handle = fopen($gmap_strings_inc_name, 'w');

// Format the data.
$label_container = '';
$label_container .= "<?php\n\nfunction gmap_marker_labels_potx() {\n";
$label_container .= "\$marker_labels = array(\n";


foreach ($marker_labels as $label_key => $label) {
  $label_container .= "$label_key";
  $label_container .= ' => ';
  $label_container .= "$label";
  $label_container .= "\n";
}
$label_container .= ')';
$label_container .= ";\n";
$label_container .= "return \$marker_labels\n";
$label_container .= '}';

// Write file.
if (fwrite($gmap_strings_inc_handle, $label_container) !== 'FALSE') {
  drupal_set_message(t('File gmap_strings.inc successfully created.'));
}
