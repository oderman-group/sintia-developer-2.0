<script type="text/javascript">
    startClass = function() {
		

        $("#notificacion").fadeOut();

        let meetingID = $("#meetingID").val();
        let moderatorPW = $("#moderatorPW").val();
        let attendeePW = $("#attendeePW").val();
        let meetingName = $("#meetingName").val();
        let username = $("#username").val();
		

        $.ajax({
            type: "POST",
            url: "../live-bbb/funciones/createMeeting.php",
            data: "meetingID=" + meetingID + "&moderatorPW=" + moderatorPW + "&attendeePW=" + attendeePW +
                "&meetingName=" + meetingName.replace(/ /g, "+") + "&username=" + username,
            dataType: "json",
            success: function(res) {
                if (res.status === 'OK') {
                    const link = document.createElement("a");
                    link.href = res.data;
                    link.download = 'video conferencia';
                    link.target = '_blank'
                    link.click();
                } else {
                    console.log(res.mensaje);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown);
            console.log(jqXHR.responseText);
        });
    }


    statusClass = function() {
        $("#notificacion").fadeOut();
        let meetingID = $("#meetingID").val();
        $.ajax({
            type: "POST",
            url: "../live-bbb/funciones/isMeetingRunning.php",
            data: "meetingID=" + meetingID,
            dataType: "json",
            success: function(res) {
                if (res.status === 'OK') {
                    let running = JSON.parse(res.data.running[0]);
                    if (running) {
                        $("#notificacion").html("La video clase esta activa");
                        $("#notificacion").fadeIn();
                    } else {
                        $("#notificacion").html("La video clase no esta activa");
                        $("#notificacion").fadeIn();
                    }
                } else {
                    console.log(res.mensaje);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown);
            console.log(jqXHR.responseText);
        });
    }

    findRecordings = function() {
        $("#notificacion").fadeOut();
        let meetingID = $("#meetingID").val();
        $.ajax({
            type: "POST",
            url: "../live-bbb/funciones/getRecordings.php",
            data: "meetingID=" + meetingID,
            dataType: "json",
            success: function(res) {
                console.log(res);
                if (res.status === 'OK') {
                    if (res.data.messageKey[0] === undefined) {
                        let data = res.data;
                        var container = $("<div></div>");
                        container.append($("<h4></h4>").text("Grabaciones: " + data.recordings.length));

                        data.recordings.forEach(function(property) {
                            var line = $("<div></div>");
                            var callName = $("<span></span>").text("* " + property.name[0] + " => Id grabacion: " + property.recordId[0]+ " = ");
                            var callLink = $("<a></a>");
                            callLink.attr("href", property.playbackFormatUrl[0]);
                            callLink.text("click aqui para acceder");
                            line.append(callName);
                            line.append(callLink);
                            container.append(line);
                        });
                        $("#notificacion").html(res.data.recordings.length);
                        $("#notificacion").fadeIn();
                    } else {
                        $("#notificacion").html("No se encontraron grabaciones");
                        $("#notificacion").fadeIn();
                    }
                } else {
                    console.log(res.mensaje);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown);
            console.log(jqXHR.responseText);
        });
    }

    listClass = function() {
        $("#notificacion").fadeOut();
        $.ajax({
            type: "POST",
            url: "../live-bbb/funciones/getMeetings.php",
            dataType: "json",
            success: function(res) {
                console.log(res.data);
                if (res.status === 'OK') {
                    let data = res.data;

                    if (data.messageKey[0] === undefined) {
                        var container = $("<div></div>");
                        container.append($("<h4></h4>").text("Clases activas: " + data.meetings
                        .length));

                        data.meetings.forEach(function(property) {
                            var line = $("<div></div>");
                            var callName = $("<span></span>").text("* " + property.meetingName[
                                0] + " => Id meeting: " + property.meetingId[0]);
                            line.append(callName);
                            container.append(line);
                        });

                        $("#notificacion").html(container);
                        $("#notificacion").fadeIn();
                    } else {
                        $("#notificacion").html("No se encontraron clases activas");
                        $("#notificacion").fadeIn();
                    }
                } else {
                    console.log(res.mensaje);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown);
            console.log(jqXHR.responseText);
        });
    }
	
	
	
	
	/* Para estudiantes */
	startClassStudent = function() {
        let meetingID = $("#meetingID").val();
        let attendeePW = $("#attendeePW").val();
        let username = $("#username").val();

        $.ajax({
            type: "POST",
            url: "../live-bbb/funciones/getJoinMeetingUrlAttendee.php",
            data: "meetingID=" + meetingID + "&attendeePW=" + attendeePW + "&username=" + username,
            dataType: "json",
            success: function(res) {
                if (res.status === 'OK') {
                    const link = document.createElement("a");
                    link.href = res.data;
                    link.download = 'video conferencia';
                    link.target = '_blank'
                    link.click();
                } else {
                    console.log(res.mensaje);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.log(errorThrown);
            console.log(jqXHR.responseText);
        });
    }

    $(document).ready(function() {
      
        $("#startClassStudent").on("click", function() {
            startClassStudent();
        });
    });
	/* Fin Para estudiantes */
	
	
	


    $(document).ready(function() {

        $("#startClass").on("click", function() {
            startClass();
        });
        $("#statusClass").on("click", function() {
            statusClass();
        });
        $("#findRecordings").on("click", function() {
            findRecordings();
        });
        $("#listClass").on("click", function() {
            listClass();
        });
    });
    </script>