const ajaxHandler = {
    submit: (e) => {
        $(`#${e}`).submit(function (e) {
            e.preventDefault();
            const a = $(this),
                s = a.find("[type='submit']"),
                t = s.html(),
                r = a.attr("action"),
                o = new FormData(this);
            $.ajax({
                beforeSend: () =>
                    s
                        .addClass("btn-loader disabled")
                        .html(
                            '<span class="me-2">Loading </span><span class="loading"><i class="ri-refresh-line fs-16"></i></span>'
                        )
                        .prop("disabled", !0),
                type: "POST",
                url: r,
                data: o,
                processData: !1,
                contentType: !1,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: (e) => {
                    s
                        .removeClass("btn-loader disabled")
                        .html(t)
                        .removeAttr("disabled"),
                        "success" === e.status
                            ? (alert(e.message),
                              $("#myTable").DataTable().ajax.reload(null, !1),
                              a.trigger("reset"),
                              $(".modal").hide(),
                              $(".modal-backdrop").remove())
                            : alert(
                                  e.message || "Failed to process data!",
                                  "Warning!"
                              ),
                        invalidFeedback(e);
                },
                error: (e) => {
                    s
                        .removeClass("btn-loader disabled")
                        .html(t)
                        .removeAttr("disabled"),
                        alert(
                            e.responseJSON?.message || "An error occurred!",
                            "Danger"
                        );
                },
            });
        });
    },
    delete: (e, a) => {
        $(`#${e}`).submit(function (e) {
            e.preventDefault();
            const s = $(this),
                t = s.find("[type='submit']"),
                r = t.html(),
                o = s.attr("action"),
                l = new FormData(this);
            $.ajax({
                beforeSend: () =>
                    t
                        .addClass("btn-loader disabled")
                        .html(
                            '<span class="me-2">Deleting </span><span class="loading"><i class="ri-refresh-line fs-16"></i></span>'
                        )
                        .prop("disabled", !0),
                type: "POST",
                url: o,
                data: l,
                processData: !1,
                contentType: !1,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: (e) => {
                    t
                        .removeClass("btn-loader disabled")
                        .html(r)
                        .removeAttr("disabled"),
                        "success" === e.status
                            ? (alert(e.message),
                              $("#myTable").DataTable().ajax.reload(null, !1),
                              $(`#${a}`).hide(),
                              $(".modal-backdrop").remove())
                            : alert(
                                  e.message || "Failed to delete data!",
                                  "Warning!"
                              );
                },
                error: (e) => {
                    t
                        .removeClass("btn-loader disabled")
                        .html(r)
                        .removeAttr("disabled"),
                        alert(
                            e.responseJSON?.message || "An error occurred!",
                            "Danger"
                        );
                },
            });
        });
    },
};

function invalidFeedback(e) {
    $.each(e.error, (e, i) => {
        const a = $(`input[name="${e}"]`);
        a.siblings(".invalid-feedback").remove(),
            a.after(`<div class="invalid-feedback d-block">${i}</div>`);
    });
}