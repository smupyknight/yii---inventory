  // Define the tour!
    var tour = {
      id: "hello-ross-user",
      onEnd: function() {
        createCookie('toured',1,365);
        window.location = "/contributor/surveys/index";
      },
      onClose: function() {
        createCookie('toured',1,365);
        window.location = "/contributor/surveys/index";
      },
      steps: [
        {
          //1
          //Welcome
          title: "Welcome",
          content: "Welcome to the new and improved Rode Survey System. This is a click-through tour to familiarize you with our new system. Please proceed by clicking the NEXT button.",
          target: "sidebar-left",
          placement: "right",
          arrowOffset: 'right'
        },
        {
          //2
          //Manage Surveys
          title: "Buttons",
          content: "Here you can select MANAGE SURVEYS which will enable you to find all surveys allocated to you. Should you want to refresh your memory on how the system works, simply click the REPLAY TOUR button at any time. Please proceed by clicking the NEXT button.",
          target: "sidebar-left",
          placement: "right",
          arrowOffset: 'right',
		      yOffset: 150
        },
        {
          //3
          //Icon Meanings
		      title: "Status Icons",
          content: "For your convenience, we have created icons which showcase the current status of a particular survey. All icons and their descriptions will be displayed in this area. Please proceed by clicking the NEXT button.",
          target: "page-content",
          placement: "top",
          arrowOffset: 'right',
		      yOffset: 140,
		      xOffset: 50
        },
		    {
          //4
          //Quarter
		      title: "Manage Surveys",
          content: "To view the surveys, you need to select the ‘quarter’ associated with the survey. For demonstration purposes, we have pre-selected these options for you. Please proceed by clicking the NEXT button.",
          target: "surveyssearch-quarter",
          placement: "left",
          arrowOffset: 'right',
		      yOffset: -15,
		      xOffset: 0
        },
		    {
          //5
          //Search
		      title: "Search",
          content: "Once you have selected the correct quarter, you need to click the SEARCH button to ensure that the filter and search take place accordingly. For demonstration purposes please proceed by clicking the NEXT button.",
          target: "surveyssearch-completed",
          placement: "right",
		      arrowOffset: 'right',
		      yOffset: -15,
		      xOffset: 0
        },
		    {
          //6
          //Status
		      title: "Status",
          content: "As indicated above, the STATUS ICONS show you the current status of a particular survey. Please proceed by clicking the NEXT button.",
          target: ".table > tbody > tr > td i",
          placement: "bottom",
          arrowOffset: 'center',
		      yOffset: 5,
		      xOffset: -160
        },
        {
          //7
          //Name
          title: "Selecting a Survey",
          content: "All surveys assigned to you will be listed here.  Simply click on the name of the survey to be able to see the questions. Please proceed by clicking the NEXT button.",
          target: ".table > tbody > tr > td",
          placement: "bottom",
          arrowOffset: 'right',
          yOffset: 0,
          xOffset: 50,
          multipage: true,
          onNext: function() {
            window.location = "/contributor/questions/index/7424"
          }
        },
		    {
          //8
          //Survey
		      title: "Question Summary",
          content: "All questions will be listed here. To get a quick overview of the questions, you can simply hover your mouse over the questions. Should you want to view and answer any specific question, please click on this green icon. Please proceed by clicking the NEXT button.",
          target: ".table > tbody > tr > td i",
          placement: "left",
          arrowOffset: 'right',
		      yOffset: -27,
		      xOffset: -5,
		      multipage: true,
          onNext: function() {
            window.location = "/contributor/questions/answer/1220/7424"
          }
        },
		    {
          //9
          //Question Answer
		      title: "Question",
          content: "Below is the full question, along with some more information, which will assist you in answering the question. Please proceed by clicking the NEXT button.",
          target: "h3",
          placement: "top",
          arrowOffset: 'right',
		      yOffset: 0,
		      xOffset: 0
        },
        {
          //10
          //Super regional
          title: "Answers",
          content: "Below are the various blocks where you can fill out all your answers accordingly. Please look at the red bar for the correct field to which you have to submit the answers. For demonstration purposes, we have pre-entered these answers for you. Please proceed by clicking the NEXT button.",
          target: ".table > thead > tr > th",
          placement: "top",
          arrowOffset: 'right',
          yOffset: 0,
          xOffset: 200
        },
        {
          //11
          //button
          title: "Buttons",
          content: "Here you have numerous options; Submit & Finish (only shown on the last questions), Submit & Continue (taking you to the next question), Previous (should you have answered a few questions and want to have another look at a particular question/answer) and Cancel. For demonstration purposes please proceed by clicking the NEXT button.",
          target: ".pull-right > button",
          placement: "top",
          arrowOffset: 'center',
          yOffset: 0,
          xOffset: -170,
          multipage: true,
          onNext: function() {
            window.location = "/contributor/questions/index/7424?demo=1"
          }
        },
        {
          //12
          //button - distibute
          title: "Complete Survey",
          content: "VERY IMPORTANT:  Once you have answered all questions, you will return to this page where you have to click the COMPLETE SURVEY button to ensure that the data you have entered is submitted. Please proceed by clicking the NEXT button.",
          target: "#demoComplete",
          placement: "top",
          arrowOffset: 'right',
          yOffset: 0,
          xOffset: 0
        },
        {
          //13
          //account
          title: "Account Details",
          content: "Here you can edit all your personal details.<br><br>Thank you for your time. Please complete this tour by clicking the DONE button.",
          target: ".navbar-profile",
          placement: "left",
          arrowOffset: 'right',
          yOffset: 0,
          xOffset: 0
        },
      ]
    };
	  
    // Start the tour!
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        var expires = "; expires=" + date.toUTCString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

    function restartTour() {
        eraseCookie('toured');
        window.location = "/contributor/surveys/index";
        //hopscotch.startTour(tour,0);
    }

    // Initialize tour if it's the user's first time
    if (!readCookie('toured')) {
        hopscotch.startTour(tour);
    }