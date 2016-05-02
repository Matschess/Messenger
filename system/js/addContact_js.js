$(document).ready(function () {
    $('body').keydown(function () {
        getKeyCode();
    });

    function getKeyCode(event) {
        event = event || window.event;
        if (event.keyCode == "13") {
            search();
        }
    }

    $('*').load(function () {
        $('#contactname').focus();
    });

    $('.searchContactname').click(function () {
        search();
    });

    $('#contactLoupe').click(function () {
        search();
    });

    function search() {
        $friend = $('#contactname').val();
        $.post("php/idToUser.php",
            {
                user: $friend,
            },
            function (data) {
                if (data == 'error') {
                    $('#foundContact').slideUp(200);
                    $('#contactLoupe').addClass('rotated');
                    setTimeout(function () {
                        $('#contactLoupe').removeClass('rotated');
                        setTimeout(function () {
                            $('#contactLoupe').addClass('rotated');
                            setTimeout(function () {
                                $('#contactLoupe').removeClass('rotated');
                            }, 100);
                        }, 100);
                    }, 100);
                }
                else {
                    if ($('#foundContact').is(':visible')) {
                        $('#foundContact').slideUp(200);
                        setTimeout(function () {
                            $('#foundContact').html(data);
                            $('#foundContact').slideDown(200);
                        }, 200);
                    }
                    else {
                        $('#foundContact').html(data);
                        $('#foundContact').slideDown(200);
                    }
                }
            });
    };

    $('#foundContact').on("click", ".contactCancel", function () {
        $('#foundContact').slideUp(200);
    });

    $('#foundContact').on("click", ".toProfile", function () {
        $user_id = $('#currentUser').val();
        $friend_id = this.id;
        $('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });

    $('#foundContact').on("click", ".contactAdd", function () {
        $user_id = $('#currentUser').val();
        $friend_id = this.id;
        $.post("php/friend_change.php",
            {
                job: 'add',
                user_id: $user_id,
                friend_id: $friend_id
            },
            function (data) {
                if (data == 'updated' || data == 'added') {
                        $message = $('.chatTextBox').html();

                        if($message) {
                            var msg = {
                                type: 'message',
                                chat_id: '3',
                                message: $message
                            };

                            //convert and send data to server
                            websocket.send(JSON.stringify(msg));
                        }

                    $('#overlay').fadeOut(200);
                    $('#popup').fadeOut(200);
                }
            });
    });
});