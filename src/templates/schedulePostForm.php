<form action="/add-post" method="POST">
    <label for="question">Question</label><br>
    <input type=text required id="question" name="question"><br>
    
    <label for="publish_unix_timestamp">Publish Unix Timestamp</label><br>
    <input
        type=number
        required
        id="publish_unix_timestamp"
        name="publish_unix_timestamp"
        min=0
        max=9999999999
    ><br>

    <input type="submit" value="Add" id="submitFormButton">
    <div class="spinner"></div>
</form>
