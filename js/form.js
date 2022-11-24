; (function ($) {
    "use strict";

    //* Form js
    function verificationForm() {
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

    };

    /*Function Calls*/
    verificationForm();
})(jQuery);

function SendDiscordAlert(content) {
    // Send AJAX request to get discord webhook link from db
    $.ajax({
        url: "api/fetch.php",
        method: "GET",
        data: {
            getWebhook: true,
            id: 1
        },
        success: function (result) {
            // on success send post to webhook link with the new registered user data
            webhookArray = JSON.parse(result);
            $.post(webhookArray["webhookLink"], {"content": content});
            console.log("Discord Notification Sent!");
        },
        error: function () {
            // failed to send notification
            console.log("Failed to Send Discord Notification!");
        }
      })
}

function manage_button(activate = false, id_button_submit) {
    // Controls a button (locked/unlocked)
    if (activate) {
        $(id_button_submit).removeClass("disabled");
    } else {
        $(id_button_submit).addClass("disabled");
    }
}

function checkNick(id, button) {
    // Check if nick is duplicated in DB
    var nick = $(id).val();
    $.ajax({
        url: "api/fetch.php",
        method: "GET",
        data: {
            check_nickname: true,
            nick: nick
        },
        success: function (result) {
            $("#duplicate_error").removeClass("show").addClass("hidden");
            console.log('Nick je dostupný.');
        },
        error: function () {
            $(id).addClass("error").removeClass("success");
            $("#duplicate_error").addClass("show").removeClass("hidden");
            checkErrors(button);
        }
    })
}

function checkErrors(button, require_valid = false, required_fields = 0) {
    // Check for the number of wrong fields in form
    var number_of_errors = $('.error').length;
    // Adiciona o número de elementos com aviso
    number_of_errors += $('.aviso').length;
    // Conta o número de elementos que contêm a classe success 
    var number_of_valid = $('.success').length;

    if (require_valid) {
        if (number_of_valid != required_fields) {
            manage_button(false, button);
            return false;
        }
    }
    // Apenas permite a submissão do formulário se este for zero e se todos campos obrigátorios estão certos
    if (number_of_errors == 0) {
        manage_button(true, button);
        return true;
    } else {
        manage_button(false, button);
        return false;
    }
}

function validateName(id, button, isCanoeFriend = false) {

    function hasSpecialchar(string) {
        // Function to test is the given nick has spacialchar
        var specialChar = /[`~!@#$%^&*()_|+\-=?;:..’“'"<>,€£¥•،٫؟»«\{\}\[\]\\\/]+/gi;
        return specialChar.test(string);
    }
    function hasSpace(string, id) {
        // Function to test is the given nick has spaces
        var spaceArray = [" "];

        jQuery.each(spaceArray, function (i, space) {

            if (string.includes(space)) {
                $(id).removeClass("success").addClass("error");
                $("#name_error").removeClass("hidden").addClass("show");
                return true;
            }
        });
        return false;
    }
    function rangeNickname(string) {
        if (string.length < 2 || string.length > 20) {
            $(id).removeClass("success").addClass("error");
            $("#name_error").removeClass("hidden").addClass("show");
            return true;
        }
    }
    const nick = $(id).val();
    // Test for special char in nickname
    if (hasSpecialchar(nick)) {
        // error
        $(id).removeClass("success").addClass("error");
        $("#name_error").removeClass("hidden").addClass("show");
    } else {
        // success
        $(id).removeClass("error").addClass("success");
        $("#name_error").removeClass("show").addClass("hidden");
    }
    // Check for empty nickname
    if (nick == "") {
        $(id).removeClass("error").removeClass("success");
        $("#name_error").removeClass("show").addClass("hidden");

        if (!isCanoeFriend) {
            manage_button(false, button);
        } else {
            checkErrors(button);
        }
        return

    }
    // Validation
    checkNick(id, button);
    hasSpace(nick, id);
    rangeNickname(nick);
    // Check for successfull validations
    manage_button(true, button);
    if (!isCanoeFriend) {
        checkErrors(button, true, 2, true);
    } else {
        checkErrors(button);
    }
}

function failed_submit(id, error_message = "Formulář se správně neodeslal, zkuste to znovu.") {
    // Function sets HTML for when form submit has failed
    setTimeout(function () {
        $(id).html(`
        <h3>Chyba!</h3>
        <h6>${error_message}</h6>
        <div class="f-modal-icon f-modal-error animate">
            <span class="f-modal-x-mark">
                <span class="f-modal-line f-modal-left animateXLeft"></span>
                <span class="f-modal-line f-modal-right animateXRight"></span>
            </span>
            <div class="f-modal-placeholder"></div>
            <div class="f-modal-fix"></div>
        </div>
        </br>
        <hr>
        <button type="button" class="action-button previous_button return_homepage">Zpět na domovskou stránku</button> 
        `)
    }, 700);
}


function validateDate(id, button) {
    // Validate a given date, date has to be in format: YYYY-MM-DD
    var value = $(id).val();
    var year = value.charAt(4);
    // Check if year is bigger than 9999 by checking the string in 4th position and analysing if its a number 
    // (wrong date ex.: bigger than 9999) or a separation symbol (correct date ex.: less than 10000)
    if (value == '' || year != '-') {
        $(id).removeClass("success").removeClass("error");
        $("#birthdate_error").addClass("hidden").removeClass("show");
        manage_button(false, button);
        return;
    }
    // with the date validated, instanciate new objects 'Date'
    var birthDate = new Date(value);
    var today = new Date();
    var age = today.getFullYear() - birthDate.getFullYear();
    // check if age is higher than the minimun required (10)
    if (age < 10 || age > 100) {
        $(id).removeClass("success").addClass("error");
        $("#birthdate_error").removeClass("hidden").addClass("show");
    } else {
        $(id).removeClass("error").addClass("success");
        $("#birthdate_error").removeClass("show").addClass("hidden");
    }
    // Final validations
    checkErrors(button, true, 2);

}
$('#submit_form').click(function () {
    // Get variables from form and submit it
    var nick = $('#nick').val();
    var birthdate = $('#birthdate').val();
    var is_swimmer = $('#is_swimmer').is(':checked') ? 1 : 0;
    var canoe_kamarad = $('#canoe_kamarad').val();
    // Fail the form submit if user is not swimmer
    if (is_swimmer == 0) {
        failed_submit('#result', "Formulář se správně neodeslal jelikož nejste plavec, zkuste to znovu.");
        return;
    }
    // AJAX Post sending data
    $.ajax({
        url: "database/upload.php",
        method: "POST",
        data: {
            apply: true,
            nick: nick,
            birthdate: birthdate,
            is_swimmer: is_swimmer,
            canoe_kamarad: canoe_kamarad
        },
        success: function (result) {
            setTimeout(function () {
                // Success
                $('#result').html(`
                <h3>Odesláno!</h3>
                <div class="success-checkmark">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
                </div>
                </br>
                <hr>
                <button type="button" class="action-button previous_button return_homepage">Zpět na domovskou stránku</button> 
                `);
            }, 700); 
            // prepare discord message and send
            var message = `Nový uživatel byl zaregistrovaný! \n
            Nickname: ${nick},\n
            Birthdate: ${birthdate},\n
            IsSwimmer: ${is_swimmer},\n
            Canoe Friend: ${canoe_kamarad}
            `;
            SendDiscordAlert(message);
        },
        error: function () {
            // Failed submition (Http 400 or Http 500)
            failed_submit('#result');
        }
    })
});

