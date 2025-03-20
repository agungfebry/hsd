const ajaxHandler = {
    submit: (e) => {
        $(`#${e}`).submit(function (event) {
            event.preventDefault();
            const form = $(this),
                submitButton = form.find("[type='submit']"),
                originalText = submitButton.html(),
                // formData     = new FormData(this),
                method = form.attr("method"),
                url = form.attr("data-url");

                let formData = {};

                if (e === "formUpdateProduct") {
                    formData = {
                        _method: "PATCH",
                        name: $(`#${e} #name`).val(),
                        price: $(`#${e} #price`).val(),
                        category_id: $(`#${e} #category_id`).val(),
                        status: $(`#${e} #status`).val(),
                    };
                } else {
                    let formArray = form.serializeArray();
                    formArray.forEach((field) => {
                        formData[field.name] = field.value;
                    });
                }

                let queryString = $.param(formData);
                let fullUrl = `${url}?${queryString}`;
                
            $.ajax({
                beforeSend: () => {
                    $(".invalid-feedback").remove();
                    submitButton
                        .addClass("btn-loader disabled")
                        .html(
                            '<span class="me-2">Loading </span><span class="loading"><i class="ri-refresh-line fs-16"></i></span>'
                        )
                        .prop("disabled", true);
                },
                type: method,
                url: fullUrl,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: (response) => {
                    $(".invalid-feedback").remove();
                    submitButton
                        .removeClass("btn-loader disabled")
                        .html(originalText)
                        .prop("disabled", false);

                    if (response.status === "success") {
                        alert(response.message);
                        $("#productTable").DataTable().ajax.reload(null, false);
                        form.trigger("reset");
                        $(".modal").modal("hide");
                    } else {
                        alert(response.message || "Failed to process data!");
                    }
                },
                error: (error) => {
                    $(".invalid-feedback").remove();
                    invalidFeedback(error.responseJSON);
                    submitButton
                        .removeClass("btn-loader disabled")
                        .html(originalText)
                        .prop("disabled", false);
                },
            });
        });
    },
    delete: (e, a) => {
        $(`#${e}`).submit(function (e) {
            e.preventDefault();
            const form = $(this),
                submitButton = form.find("[type='submit']"),
                originalText = submitButton.html(),
                o = form.attr("data-url"),
                l = new FormData(this);
            const idProduct = l.get("id");

            url = o + "/" + idProduct;

            $.ajax({
                beforeSend: () =>
                    submitButton
                        .addClass("btn-loader disabled")
                        .html(
                            '<span class="me-2">Deleting </span><span class="loading"><i class="ri-refresh-line fs-16"></i></span>'
                        )
                        .prop("disabled", !0),
                type: "POST",
                url: url,
                data: l,
                processData: !1,
                contentType: !1,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: (response) => {
                    submitButton
                        .removeClass("btn-loader disabled")
                        .html(originalText)
                        .prop("disabled", false);

                    if (response.status === "success") {
                        alert(response.message);
                        $("#productTable").DataTable().ajax.reload(null, false);
                        form.trigger("reset");
                        $(".modal").modal("hide");
                    } else {
                        alert(response.message || "Failed to process data!");
                    }
                },
                error: (error) => {
                    invalidFeedback(error.responseJSON);
                    submitButton
                        .removeClass("btn-loader disabled")
                        .html(originalText)
                        .prop("disabled", false);
                },
            });
        });
    },
};

function invalidFeedback(e) {
    $.each(e.errors.message, (e, i) => {
        const a = $(`input[name="${e}"]`);
        a.siblings(".invalid-feedback").remove(),
            a.after(`<div class="invalid-feedback d-block">${i}</div>`);
    });
}
