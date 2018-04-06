/*
 ----------------------------
 ajaxValidation-functionality
 ----------------------------
 */

$(document).ready(function () {

    /*
     *  Handle autoSubmit=onChange functionality.
     */
    $('[data-autosubmit="onChange"]').on("change", function (e) {
        $(this).closest('form').submit();
    });

    /*
     *  Handle onSubmit ajax-validation of whole forms.
     */
    $('form[data-ajaxvalidation="onSubmit"]').on("submit", function (e) {

        // Put the form-object in a variable - we need it later after ajax-query.
        var jqForm = $(this);

        // Init some variables
        var submitForm = true;
        var performAjaxValidation = true;

        // We do not make an ajax-validation, if a captcha-field is present,
        // because a complete reload is necessary to get the currently valid captcha-question.
        if (jqForm.find('[name="_captcha"]').length > 0) {
            performAjaxValidation = false;
        }

        if (performAjaxValidation) {

            $.ajax({
                url: '/formfactory_validation', // form action url
                type: 'POST', // form submit method get/post
                dataType: 'json', // request type html/json/xml
                data: $(this).serialize(), // serialize form data
                async: false,    // must be false to allow validation
                beforeSend: function () {

                    // Clear all error elements before submit.
                    clearErrors(jqForm);

                },
                success: function (data, textStatus, jqXHR) {

                },
                error: function (jqXhr) {

                    // Per default, we do not submit the form, if an error occurred.
                    submitForm = false;

                    // Redirect if not authenticated user.
                    if (jqXhr.status === 401) {
                        $(location).prop('pathname', 'auth/login');
                    }

                    // If a validation error occurred...
                    if (jqXhr.status === 422) {

                        // Get the full response.
                        var jsonResponse = jqXhr.responseJSON;

                        // If we received an error for the captcha-field,
                        // we must submit the form in all cases to get a currently valid captcha.
                        if (jsonResponse.errors['_captcha']) {
                            submitForm = true;
                        }
                        else {
                            $.each(jsonResponse.errors, function (fieldName, fieldErrors) {
                                displayFieldErrors(fieldName, fieldErrors, jqForm);
                            });
                        }
                    }

                    // A 500 error may indicate a token mismatch, so we submit the form to get a new one.
                    if (jqXhr.status === 500) {
                        submitForm = true;
                    }
                }
            });

        }

        // Prevent submission of form, if validation has failed.
        if (!submitForm) {
            e.preventDefault();
        }
    });

    /*
     *  Handle onChange ajax-validation of a single field.
     */
    $('[data-ajaxvalidation="onChange"]').on("change", function (e) {
        validateField($(this));
    });

    /*
     *  Handle onKeyup ajax-validation of a single field.
     */
    $('[data-ajaxvalidation="onKeyup"]').on("keyup", function (e) {
        validateField($(this));
    });

    /*
     *  Clears errors within container.
     */
    function clearErrors(container) {
        container.removeClass('has-error');
        container.find('[data-field-wrapper]').removeClass('has-error');
        container.find('[data-error-container]').empty().hide();
    }

    /*
     *  Validate a single field via ajax.
     */
    function validateField(jqField) {

        $.ajax({
            url: 'formfactory_validation', // form action url
            type: 'POST', // form submit method get/post
            dataType: 'json', // request type html/json/xml
            data: jqField.closest('form').serialize(), // serialize form data
            async: false,
            beforeSend: function () {

                // Clear all error elements in this field-wrapper before submit.
                clearErrors(jqField.closest('[data-field-wrapper]'));

            },
            success: function (data, textStatus, jqXHR) {

            },
            error: function (jqXhr) {

                if (jqXhr.status === 422) {

                    // Get the full response.
                    var jsonResponse = jqXhr.responseJSON;

                    // Get the field-name
                    var fieldName = jqField.attr('name');

                    // Get errors for this field.
                    var fieldErrors = jsonResponse.errors[fieldName];

                    if (fieldErrors) {
                        displayFieldErrors(fieldName, fieldErrors, jqField.closest('form'));
                    }

                } else {
                    /// do some thing else
                }
            }
        });
    }

    /*
     *  Displays errors for fields within their designated error-containers..
     */
    function displayFieldErrors(fieldName, fieldErrors, jqForm) {

        var jqErrorContainer = null;

        // Find the error-container responsible for displaying errors for the field
        jqForm.find('[data-error-container]').each(function (i) {
            var jqErrorContainerCandidate = $(this);
            var displaysErrorsFor = jqErrorContainerCandidate.attr('data-displays-errors-for').split('|');
            displaysErrorsFor.forEach(function (displaysErrorsForFieldName) {
                if (displaysErrorsForFieldName === fieldName) {
                    jqErrorContainer = jqErrorContainerCandidate;
                }
            });
        });

        // If no suitable error-container was found, we use the general-error container of the form.
        if (jqErrorContainer === null) {
            jqErrorContainer = jqForm.children('[data-displays-general-errors]').first();
        }

        // Put errors inside the error-container.
        $.each(fieldErrors, function (errorKey, errorText) {
            jqErrorContainer.append('<div>' + errorText + '</div>');
        });

        // Unhide the error-container.
        jqErrorContainer.removeAttr('hidden');
        jqErrorContainer.show();

        // Set the field-wrapper this field belongs to to 'has-error'.
        var jqField = jqForm.find('[name="' + fieldName + '"]');
        jqField.closest('[data-field-wrapper]').addClass('has-error');
    }

});

/*
----------------------------
dynamicList-functionality
----------------------------
*/

$(document).ready(function () {

    // After loading the page, we evaluate every dynamicList.
    $('[data-dynamiclist-add]').each(function (i) {
        if ($(this).parents('[data-dynamiclist-template]').length === 0) {
            evaluateDynamicList($(this).data('dynamiclist-group'));
        }
    });

    // Handle adding of a dynamicList item.
    $(document).on("click", '[data-dynamiclist-add]', function () {

        // Get the group-identifier of this dynamicList.
        var groupID = $(this).data('dynamiclist-group');

        // Add a new item.
        addDynamicListItem(groupID);

        // And re-evaluate the dynamicList afterwards.
        evaluateDynamicList(groupID);
    });

    // Handle removing of a dynamicList item.
    $(document).on("click", '[data-dynamiclist-remove]', function () {

        // Get the item the remove-button belongs to.
        var item = $(this).closest("[data-dynamiclist-id]");

        // Get the group-identifier of this dynamicList.
        var groupID = item.data('dynamiclist-group');

        // Remove the item the remove-button belongs to.
        item.remove();

        // And re-evaluate the dynamicList afterwards.
        evaluateDynamicList(groupID);

    });

});

function evaluateDynamicList(groupID) {

    // Count the current items of dynamicList.
    var itemCount = $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-id]').not('[data-dynamiclist-template]').length;
    if (itemCount == null) {
        itemCount = 0;
    }

    // Get the add-button-element for this dynamicList, since minimum and maximum item-count is set via data-attributes there.
    var groupAddButton = $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-add]');

    // Get the minimum item count of this dynamicList (default is 1).
    var minimumItems = groupAddButton.data('dynamiclist-min');
    if (minimumItems == null) {
        minimumItems = 1;
    }

    // Get the maximum item count of this dynamicList (default is 10).
    var maximumItems = groupAddButton.data('dynamiclist-max');
    if (maximumItems == null) {
        maximumItems = 10;
    }

    // If the table has reached the maximum item count,...
    if ((maximumItems != null) && (itemCount >= maximumItems)) {

        // ...we display a maximum-reached-message, but only if minimumItems and maximumItems is not identical (since it makes no sense then).
        if (minimumItems != maximumItems) {
            $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-maxalert]').show().removeAttr('hidden');
        }

        // ...and hide the add-item-button.
        groupAddButton.hide();
    }
    // Otherwise, ...
    else {

        // ...we make sure, the maximum-reached-message is hidden and add-item-button is shown.
        $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-maxalert]').hide();
        groupAddButton.show();

        // Additionally, we check if the current item-count is below the minimum item-count.
        if (itemCount < minimumItems) {

            // If it is the case, we add items to reach the required minimum.
            var i = itemCount;
            while (i < minimumItems) {
                addDynamicListItem(groupID);
                i++;
            }

            // We then reevaluate.
            evaluateDynamicList(groupID);
        }
    }
}

function addDynamicListItem(groupID) {

    // Get the highest id of the items of this group and add 1 for the id of the new item.
    var newItemID = 0;
    $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-id]').each(function (index) {
        var itemID = $(this).data('dynamiclist-id');
        itemID = parseInt(itemID);
        if (itemID >= newItemID) {
            newItemID = itemID + 1;
        }
    });

    // Get the template for the new item.
    var template = $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-template]');

    // Clone the template for the new item.
    var newItem = template.clone();

    // Remove the hidden-property.
    newItem.removeAttr('hidden');
    newItem.removeAttr('style');

    // Remove the data-dynamiclist-template-attribute.
    newItem.removeAttr('data-dynamiclist-template');

    // Enable all input fields, that do not belong to a nested dynamic list.
    newItem.find('[disabled]').each(function (index) {
        if ($(this).parents('[data-dynamiclist-template]').length === 0) {
            $(this).prop("disabled", false);
        }
    });

    // Get the outer HTML for the new item.
    var newItemHtml = newItem[0].outerHTML;

    // Replace the itemID-placeholder of the template with the newItemID.
    var replaceRegex = new RegExp('%group' + groupID + 'itemID%', "g");
    newItemHtml = newItemHtml.replace(replaceRegex, newItemID);

    // The template might include further child-dynamic-list-templates.
    // In this case, the child-templates contain the %parentItemID%-marker,
    // wich we must replace with the newItemID to prevent duplicate group-IDs.
    replaceRegex = new RegExp('%parentItemID%', "g");
    newItemHtml = newItemHtml.replace(replaceRegex, newItemID);

    // Add the new item just before the template.
    template.before(newItemHtml);

    // And set focus on the first input-element of it.
    $('[data-dynamiclist-group="' + groupID + '"][data-dynamiclist-id=' + newItemID + '] :input[type="text"]').first().focus();

}


/*
 ----------------------------
 filechange-functionality
 ----------------------------
 */
$(function () {
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    // We can watch for our custom `fileselect` event like this
    $(':file').on('fileselect', function (event, label) {
        $(this).parents('.input-group').find(':text').val(label);
    });
});

/*
 ----------------------------
 openmodalonload-functionality
 ----------------------------
 */
$(document).ready(function () {

    // After loading the page, show all modals,
    // whose ID is present somewhere in a "data-openmodalonload"-data-attribute.
    $('[data-openmodalonload]').each(function (i) {
        var modalId = $(this).data('openmodalonload');
        $('#' + modalId).modal({show: true});
    });

});

/*
 ----------------------------
 Functionality to automatically scroll and set focus to the first 'error' element of the page.
 This is useful for usability and accessibility.
 ----------------------------
 */
$(document).ready(function () {
    var firstVisibleError = $('div[data-error-container]:visible:first');
    if (firstVisibleError.length > 0) {
        $("html").animate({
            scrollTop: firstVisibleError.offset().top - 100
        }, '300');
    }
    $(firstVisibleError).focus();
});