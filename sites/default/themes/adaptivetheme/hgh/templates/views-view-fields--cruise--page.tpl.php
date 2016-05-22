<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

// dpm($row);

$node_path = drupal_get_path_alias('node/'.$row->nid);
$thumbnail = image_style_url('tour_thumbnail', $row->field_field_thumbnail[0]['raw']['uri']);		
$klass = 'hotel-item';

if ($index == $total - 1) $klass = 'hotel-item last';

$html = '<div class="page-hotels"><div class="hotels-tab-content">';
$html .= '<div class="'.$klass.'" id="cruise-item-'.$row->nid.'">
<div class=""><h2>'.l($row->node_title, 'node/'.$row->nid).'</h2>';
$html .= '<span class="class"> - '.($row->field_field_star[0]['rendered']['#markup']).'</span>';

if (isset($row->field_field_star)) {
	$hotel_star = _hotel_star($row->field_field_star[0]['raw']['value']);		
    for ($i = 0; $i < $hotel_star; $i++) {
        if ($i == 0) $html .= '<span class="first star"></span>';
        else $html .= '<span class="star"></span>';	
    }
}

$html .= '<div class="clear"></div>';
$html .= '<div class="info">
	<div class="thumbnail">'.l('<img src="'.$thumbnail.'" class="hotel-thumbnail" />', 'node/'.$row->nid, array('html' => true)).'</div>
	<div class="detail">'.$row->field_field_teaser[0]['rendered']['#markup'].'</div>';

$html .= '<div class="button">'.l(t('Send Inquiry'), 'inquiry/'.$node_path, array('attributes' => array('class' => ''))).'</div>';
$html .= '<div class="button">'.l(t('View Cruise'), 'node/'.$row->nid, array('attributes' => array('class' => ''))).'</div>';

$html .= '</div>';

$html .= '</div>';
$html .= '</div></div></div>'; // hotel-item

print $html;
