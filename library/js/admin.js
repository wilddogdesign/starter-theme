// ONLY RUNS IF ON ADMIN PAGE

var editPageType = document.getElementsByClassName("post-type-acf-field-group");
if (editPageType.length !== 0) {
  overwriteFieldGroupKey();
}

// Used to intercept the field group key before the ACF Save function.
function overwriteFieldGroupKey() {
  var hiddenInputFields = document.getElementsByName("acf_field_group[key]");
  var fieldGroupNames = document.getElementsByName("post_title");
  var fieldGroupName = fieldGroupNames[0].value;
  var convertedFieldGroupName = fieldGroupName
    .replace(/\s+/g, "-")
    .toLowerCase();
  hiddenInputFields[0].value = convertedFieldGroupName;
}
