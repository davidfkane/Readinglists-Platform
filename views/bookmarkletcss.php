<?php
header("Content-type: text/css");
# bookmarklet css.
?>
#outerRLForm{
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAEAgMAAADUn3btAAAAA3NCSVQICAjb4U/gAAAADFBMVEX///9PT08zMzMAAAA9qPtQAAAABHRSTlMAqqqqBZeguQAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNXG14zYAAAAQSURBVAiZY3jGsJMhj2E2AAvUAqn/vjlgAAAAAElFTkSuQmCC);
}
#innerRLForm {
	font-family: verdana, Arial, Helvetica, sans-serif;
    font-size: 10pt;
    line-spacing: 1;
    border-spacing: 0px;
    border-collapse:collapse;
	border-spacing:0px;
    
	opacity: 100; 
    border: solid 1px black; 
    width: 320px; 	
    background-color: white; 
    padding: 0px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border:1px solid #CCC;
    text-align: center;
}

#innerRLForm input, #innerRLForm textarea, #innerRLForm select, span#rlclosewindow{
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border:1px solid #CCC;
	background-color: #c0c0c0;
    margin: 3px;
    width: 300px;
    float: left;
} 
#innerRLForm p{ 
    padding: 0px; margin: 0px;
    line-height: 1;  
    border: none;
    margin: 0px;    
}
#innerRLForm input#rlsubmitbutton{
	width: 200px; 
    border:1px solid saddlebrown;
    text-shadow: 0 1px 0 yellow;
	background-color: yellow;
    color: saddlebrown;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAWBAMAAADz6OuZAAAAA3NCSVQICAjb4U/gAAAAMFBMVEX/owD/zwD/sgD/4wD/zAD/4AD/xAD/2gD/pQD/vgD/1wD/rQD/uQD/0wD/qAD/3QDc91W7AAAACXBIWXMAAAsSAAALEgHS3X78AAAAFnRFWHRDcmVhdGlvbiBUaW1lADA4LzIxLzEzb1z9+QAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNXG14zYAAAAbSURBVAiZYzBgQMACMBQAwgQgPACEG4CwAQYBl/wIsVcduUEAAAAASUVORK5CYII=);
    background-repeat: repeat-x;
    background-position: bottom;
    cursor: pointer;
   }
#innerRLForm span#rlclosewindow{
	width: 50px; 
    border:1px solid darkgreen;
	background-color: #D9FFD9;    
    text-shadow: 0 1px 0 white;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAPBAMAAADXMbjKAAAAA3NCSVQICAjb4U/gAAAAHlBMVEWq6KTA873W/tav6qrL+Mm68La17bDR+8/F9cPZ/9nuh926AAAACXBIWXMAAAsSAAALEgHS3X78AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M1cbXjNgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwOC8yMS8xM29c/fkAAAAcSURBVAiZY5jAAIIFDA4MDQwCDAEMCQwGDHAAAFMuA9FjGW9MAAAAAElFTkSuQmCC);
    color: darkgreen;
    cursor: pointer;
    padding: 1px;
    background-repeat: repeat-x;
    background-position: bottom;  
   }
#innerRLFormTitle {
	padding: 3px; 
    font-size: 16px; 
    padding-bottom: 8px; 
    vertical-align: middle;
}
#innerRLFormCloseButton {float: right; margin: 0px; cursor:pointer; clear:both; }
#innerRLFormContent {padding: 3px;}

