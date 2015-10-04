String.prototype.startsWith = function(needle) {
	return (this.indexOf(needle) == 0);
};

function createChart() {
var gaugeChart = AmCharts.makeChart( "chartdiv", {
  "type": "gauge",
  "theme": "none",
  "axes": [ {
    "axisAlpha" : 0,
    "tickAlpha" : 0,
    "labelsEnabled": false,
    "bands": [ {
      "color": "#ff0000",
      "innerRadius": "55%",
      "endValue": 190,
      "startValue": 180  
    }, {
      "color": "#ff1919",
      "innerRadius": "55%",
      "endValue": 180,
      "startValue": 170
    }, {
      "color": "#ff3333",
      "innerRadius": "55%",
      "endValue": 170,
      "startValue": 160  
    }, {
      "color": "#ff4d4d",
      "innerRadius": "55%",
      "endValue": 160,
      "startValue": 150
    }, {
      "color": "#ff6666",
      "innerRadius": "55%",
      "endValue": 150,
      "startValue": 140  
    }, {
      "color": "#ff8080",
      "innerRadius": "55%",
      "endValue": 140,
      "startValue": 130  
    }, {
      "color": "#ff9999",
      "innerRadius": "55%",
      "endValue": 130,
      "startValue": 120  
    }, {
      "color": "#ffb2b2",
      "innerRadius": "55%",
      "endValue": 120,
      "startValue": 110  
    }, {
      "color": "#e0e0eb",
      "innerRadius": "55%",
      "endValue": 110,
      "startValue": 100  
    }, {
      "color": "#f0f0f5",
      "innerRadius": "55%",
      "endValue": 100,
      "startValue": 90  
    }, {
      "color": "#e0e0eb",
      "innerRadius": "55%",
      "endValue": 90,
      "startValue": 80  
    }, {
      "color": "#b2b2ff",
      "endValue": 80,
      "innerRadius": "55%",
      "startValue": 70
    }, {
      "color": "#9999ff",
      "endValue": 70,
      "innerRadius": "55%",
      "startValue": 60
    },  {
      "color": "#8080ff",
      "endValue": 60,
      "innerRadius": "55%",
      "startValue": 50
    },  {
      "color": "#6666ff",
      "endValue": 50,
      "innerRadius": "55%",
      "startValue": 40
    },  {
      "color": "#4d4dff",
      "endValue": 40,
      "innerRadius": "55%",
      "startValue": 30
    },  {
      "color": "#3333ff",
      "endValue": 30,
      "innerRadius": "55%",
      "startValue": 20
    },  {
      "color": "#1919ff",
      "endValue": 20,
      "innerRadius": "55%",
      "startValue": 10
    },  {
      "color": "#0000ff",
      "endValue": 10,
      "innerRadius": "55%",
      "startValue": 0
    } ],
    "endValue": 190
  } ],
  "arrows": [ {} ],
  "export": {
    "enabled": true
  }
} );
	return gaugeChart;
}

function analyze(data) {
	var obj = JSON.parse(data);

	var con = obj.results.Conservative;
	var lib = obj.results.Liberal;
	var green = obj.results.Green;
	var tar = obj.results.Libertarian;

	window.result = "";

	window.finalRightist = con + tar;
	window.finalLeftist = lib + green;
	window.theMax = Math.max( finalLeftist, finalRightist );
	var theDifference = Math.abs( finalLeftist - finalRightist );
	if (theDifference <= 0.26){
		window.result = "Moderate";
		return "<span style='color: grey;'>Moderate</span>";
	}

	if (window.theMax == window.finalRightist){
		window.result = "Conservative";
		return "<span style='color: red;'>Conservative</span>";
	} else if (window.theMax == window.finalLeftist){
		window.result = "Liberal";
		return "<span style='color: blue;'>Liberal</span>";
	} else {
		return "Indeterminate";
	}
}

document.addEventListener('DOMContentLoaded', function() {
	chrome.tabs.getSelected(null, function(tab) {
		// Don't check pages that are part of Google Chrome, such as the preferences pane and the
		// new tab window with the search bar.
		if (tab.url.startsWith("chrome://")) {
			$("#wait-cursor").hide();
			$("#content").append("<p>This extension does not work on pages that are part of Google Chrome.</p>");
			return;
		}

		// If the user has an actual HTTP or HTTPS URL, submit it to the the APIs. This works in two
		// stages: first, we have to extract the article's text using AlchemyAPI, then we pass the
		// data to Indico, which will analyze the text and tell us the political slant.
		$.post( "http://gateway-a.watsonplatform.net/calls/url/URLGetText?apikey=cc5ce465c9859bb7f7fcfdfba29dbd257140dc94", { outputMode: "json", url: tab.url }, function( data ) {
			//alert( tab.title );
			
			$.post( "http://apiv2.indico.io/political?key=5a270b8e83870c2dab1e6fb14fc6adc5", { data: data.text }, function( data ) {
				//alert( "Data Loaded: " + data );
				
				$("#wait-cursor").hide();
				$("#content").append("<p>Based on our analysis, the political slant of this article is</p>");
				$("#content").append("<p id='result'>" + analyze(data) + "</p>");
				$("#content").append("<div id='chartdiv'></div> ");
				
				var gaugeChart = createChart();
				$("a[title='JavaScript charts']").hide();

				// Calculate how far to move up the gauge on the political spectrum graphic.
				var value = 0;
				if (window.theMax == window.finalRightist) {
					value = window.theMax * 190;
				} else if (window.theMax == window.finalLeftist) {
					if (window.theMax * 190 < 95) value = window.theMax * 190;
					else value = 190 - (window.theMax * 190);
				}

				// Print debugging info
				//$("#content").append("<p>" + value + "</p>");
				//$("#content").append("<p>" + window.finalLeftist + " " + window.finalRightist + "</p>");

				// Set the gauge to show the proper value
				if ( gaugeChart ) {
					if ( gaugeChart.arrows ) {
						if ( gaugeChart.arrows[0] ) {
							if ( gaugeChart.arrows[0].setValue ) {
								gaugeChart.arrows[0].setValue( value );
							}
						}
					}
				}
				
				// Submit the political data to the server to be stored.
				$.ajax({
					type: 'POST',
					url: 'http://lampstack5-635795001885558423.cloudapp.net/politilt/index.php',
					data: { title: tab.title, url: tab.url, liberal: window.finalLeftist, conservative: window.finalRightist, score: value, result: window.result},
					dataType: 'json'
				}).done( function( data ) {
					console.log('done');
					$("#content").append("<p><a id='learn-more' href=\"" + data + "\", target=\"_blank\")'>Learn More</a></p>");
					console.log( data );
				}).fail( function( data ) {
					console.log('fail');
					$("#content").append("<p>Can't upload information to server.</p>");
					console.log(data);
				});
			});
		});
	});
}, false);
