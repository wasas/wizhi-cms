jQuery(document).ready(function ($) {

    // ---------------------------------------------------------
    // 喜欢功能
    // ---------------------------------------------------------
    $(document).on("click", ".jlk", function (e) {
        e.preventDefault();
        var task = $(this).attr("data-task");
        var post_id = $(this).attr("data-post_id");
        var nonce = $(this).attr("data-nonce");

        $(".status-" + post_id).html("&nbsp;&nbsp;").addClass("loading-img").show();

        $.ajax({
            type: "post",
            async: false,
            dataType: "json",
            url: '/like/',
            data: {
                task: task,
                post_id: post_id,
                nonce: nonce
            },
            success: function (response) {
                $(".lc-" + post_id).html(response.like);
                $(".unlc-" + post_id).html(response.unlike);
                $(".status-" + post_id).removeClass("loading-img").empty().html(response.msg);

                $(".watch-action").addClass("voted");
            }
        });
    });

});
