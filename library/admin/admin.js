jQuery(function () {
  var editPageType = document.getElementsByClassName("post-type-acf-field-group");

  if (editPageType.length) {
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
  
  var mapPinInput = document.getElementById('map-pin-input');

  if (mapPinInput) {
    var inputs = mapPinInput.querySelectorAll('input[type="radio"');

    Array.from(inputs).forEach(function (input) {
      input.insertAdjacentHTML(
        'afterend',
        '<img src="/app/themes/bedrock-theme/static/images/maps/key/' + input.value + '.svg" style="width: 30px; height: 30px; margin: 0 5px; vertical-align: bottom;">'
      );
    });
  }

  /**
   * Auto submit post filters
   */
  // var postFiltersForm = document.getElementById('posts-filter');

  // if (postFiltersForm) {
  //   postFiltersForm.addEventListener('input', function (ev) {
  //     if (ev.target.id != 'post-search-input') {
  //       postFiltersForm.submit();
  //     }
  //   });
  // }


  /**
   * Sortable lists
   */

  var sortableEl = document.querySelector('.sortable-list');

  if (sortableEl) {
    var sortable = new Sortable.default(sortableEl, {
      draggable: 'li',
      handle: '.sortable-list-grab'
    });

    var inputEl = document.querySelector('#' + sortableEl.dataset.sortInput + ' input');
    var order = [];

    function setOrder() {
      order = [];

      var items = document.querySelectorAll('.sortable-list li');

      Array.from(items).forEach(function (item, index) {
        var id = item.getAttribute('data-id');

        order.push(id);

        item.querySelector('.sortable-list-number').innerText = index + 1;
      });

      inputEl.value = order.join();
    }

    setOrder();

    sortable.on('sortable:stop', function () {
      setTimeout(function () {
        setOrder();
      }, 0);
    });
  }
});

/**
 * Disabled acf inputs when related input is active
 */

var relatedInputWrapper = document.querySelector('.js-disable-others-when-related');

if (relatedInputWrapper) {
  var relatedInput = relatedInputWrapper.querySelector('select');

  function toggleInputs(disabled) {
    var inputToDisabledWrappers = document.querySelectorAll('.js-disabled-when-related');

    Array.from(inputToDisabledWrappers).forEach(function (wrapper) {
      var inputs = wrapper.querySelectorAll('input,select,textarea');

      Array.from(inputs).forEach(function (input) {
        input.disabled = disabled;
      });
    });
  }

  toggleInputs(!!relatedInput.value);

  jQuery(relatedInput).on('select2:select', function (ev) {
    toggleInputs(true);
  });

  jQuery(relatedInput).on('select2:unselect', function (ev) {
    toggleInputs(false);
  });
}