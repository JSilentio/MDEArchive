
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>MDE</title>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
  <body>
    <div id="content"></div>
  </body>
</html>
<style>
.body{
  font-family: Helvetica;
  font-size: 11pt;
}
.wrapper{
  padding: 10px;
  display: flex;
  flex-direction: column;
}
.comment{
  width: 100%;
  border: 1px #666 solid;
  margin: 10px;
  min-height: 100px;
  background-color: #ddd;
}
.reply{
  width:98%;
  border: 1px #999 solid;
  margin-top: 10px;
  clear:both;
  min-height: 80px;
  background-color: #eee;
}
.updoots{
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid black;
  float: left;
}
.score{
  float: left;
  font-size: 8pt;
  width: 30px;
  height: 20px;
}
</style>
<script>

function htmlOutput(html) {

  content = $('#content').html();

  $('#content').html(content+html);
}

function timeSince(date) {
    var seconds = Math.floor(((new Date().getTime()/1000) - date))

    var interval = Math.floor(seconds / 31536000);

    if (interval >= 1) {
      if(interval == 1) return interval + " year ago";
      else
        return interval + " years ago";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) {
      if(interval == 1) return interval + " month ago";
      else
        return interval + " months ago";
    }
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) {
      if(interval == 1) return interval + " day ago";
      else
        return interval + " days ago";
    }
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) {
      if(interval == 1) return interval + " hour ago";
      else
        return interval + " hours ago";
    }
    interval = Math.floor(seconds / 60);
    if (interval >= 1) {
      if(interval == 1) return interval + " minute ago";
      else
        return interval + " minutes ago";
    }
    return Math.floor(seconds) + " seconds ago";
  }

function processPost(post) {
  html = "";

  postData = post[0]['data']['children']
  //listing[0]['data']['children'][0]['data']

  for(var i=0, l=postData.length; i<l; i++) {
      var obj = postData[i].data;

      var votes     = obj.score;
      var title     = obj.title;
      var subtime   = obj.created_utc;
      var thumb     = obj.thumbnail;
      var subrdt    = "/r/"+obj.subreddit;
      var redditurl = "http://www.reddit.com"+obj.permalink;
      var subrdturl = "http://www.reddit.com/r/"+obj.subreddit+"/";
      var exturl    = obj.url;

      var timeago = timeSince(subtime);

      if(obj.thumbnail === 'default' || obj.thumbnail === 'nsfw' || obj.thumbnail === '')
        thumb = 'img/default-thumb.png';

      html += '<img src="'+thumb+'" class="thumbimg">\n';
      html += '<div class="linkdetails"><h2>'+title+'</h2>\n';
      html += '    <p class="subrdt">posted to <a href="'+subrdturl+'" target="_blank">'+subrdt+'</a> '+timeago+'</p>';
      html += '</div>';
    }
  htmlOutput(html);
}

function processComments(comments) {
  html = "";
  $.each(comments[1]['data']['children'], function(key, comment) {
    html += '<div class="comment">';
    addComment(comment, 1);
    html += '</div>\n';
  });

}

function addComment(comment, recursionLevel){

  var obj = comment['data'];

  if(obj.body != "") {
        var score     = obj.score;
        var body      = obj.body;
        var author    = obj.author;

        html += '<div class="wrapper">\n';
        html += '<div class="body"><div class="score"><div class="updoots"></div>'+score+'</div><b>'+author+'</b>:<br>'+body+'\n</div>';

        $.each(comment['data']['replies'], function(key2, replies) {
            $.each(replies['children'], function(key3, reply){
                if(reply['data'].body){
                    html += '<div class="reply">\n';
                    addComment(reply, recursionLevel + 1);
                    html += '</div>\n';
                }
            });
        });

        html += '</div>\n'; // close wrapper


        // we use recursionLevel to know when we're back at the top of a comment chain.
        // once we're there, we know we can close it out and there are no other sub-chains to loop through
        recursionLevel--;

        if(recursionLevel == 0){
          htmlOutput(html);
          html = "";
        }

    }
}

function run() {

  processPost(json);
  processComments(json);

}
</script>

