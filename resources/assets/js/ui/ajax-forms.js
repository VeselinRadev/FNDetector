window.Forms = {

	submit: function() {

		if ($(this).data("loading")) {
			return;
		}

		var data = window.Forms.getData(this);
		var $this = $(this);

		var showValidationErrors = function(errors) {

			for (var key in errors) {

				var text = errors[key][0];

				var keySplit = key.split("\.");
				var inputName = keySplit[0];

				for (var i = 1, len = keySplit.length; i < len; i++) {
					inputName += "[" + keySplit[i] + "]";
				}

				var input = $this.find("[name='" + inputName + "'], [name='" + inputName + "[]" + "']");

				if (input.is("[type=checkbox], [type=radio]")) {
					var errorContainer = input.closest(".form__group");

					errorContainer.addClass("form__group--has-error ");

					errorContainer.append(
						'<div class="form__error">' + text + '</div>'
					);

				}
				else {
					var errorContainer = input.closest(".form__group");
					errorContainer.addClass("form__group--has-error");

					errorContainer.append(
						'<div class="form__error">' + text + '</div>'
					);
				}


			}

		};

		var clearValidationErrors = function() {
			$this.find(".form__error").remove();
			$this.find(".form__group--has-error").removeClass("form__group--has-error");
		}

		$.ajax({
			url: $this.attr("action"),
			method: $this.attr("method"),
			data: data,
			processData: false,
			contentType: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				$this.data("loading", true);
				$this.find("button[type=submit], button:not([type])")
					.addClass("btn--loading")
					.attr("disabled", true)
					.prop("disabled", true);
			},
			success: function(response) {
				clearValidationErrors();

				if (response) {

					if (typeof response["redirect"] != "undefined") {
						window.location = response["redirect"];
					}

					if (typeof response["message"] != "undefined")
					{
						Popup.showMessage(response["message"]);

						if ($this.attr("data-clear-after"))
						{
							$this.find("input[type=text], input[type=email], input[type=file], textarea").val("").change();
							$this.find("input[type=checkbox]:checked, input[type=radio]:checked").removeProp("checked").removeAttr("checked").prop("checked", false).change();
							$this.find(".js-repeaters > .js-form-repeater:not(:first-child)").remove();
						}

						$this.find("[data-clear-after]").val("").change();
					}
				}

			},
			error: function(jqXhr) {
				clearValidationErrors();
				var status = jqXhr.status;
				var response = jqXhr.responseText;
				
				if (status == 422) { // Validation errors
					var message = $.parseJSON(response);
					showValidationErrors(message.errors);
				}
				else if (status == 500) {
					alert("Грешка на сървъра");
				}
				else {
					alert("Грешка");
				}
			},
			complete: function() {
				setTimeout(function() {

					$this.find("button[type=submit], button:not([type])")
						.removeClass("btn--loading")
						.removeProp("disabled")
						.removeAttr("disabled");
					$this.data("loading", false);

				}, 500);
			}
		});


	},

	getData: function(form) {
		return new FormData(form);
	}

}

$(document).on("submit", "[data-ajaxify]", function(e) {
	e.preventDefault();
	window.Forms.submit.call(this);
});

$(document).on("submit", "form:not([data-ajaxify])", function(e) {
	$(this).find("button[type=submit], button:not([type])").addClass("loading");
});