{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<div class="block">
<label>{'Default value'|i18n( 'design/standard/class/datatype' )}:</label>
<select name="ContentClass_ezdate_default_{$class_attribute.id}">
    <option value="0" {section show=eq( $class_attribute.data_int1, 0 )}selected="selected"{/section}>{'Empty'|i18n( 'design/standard/class/datatype' )}</option>
    <option value="1" {section show=eq( $class_attribute.data_int1, 1 )}selected="selected"{/section}>{'Current date'|i18n( 'design/standard/class/datatype' )}</option>
</select>
</div>