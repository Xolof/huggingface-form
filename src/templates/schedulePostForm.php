<h3>Schedule a post for the blog.</h3>
<form action="/add-post" method="POST">
    <label for="question">Question for the AI:</label>
    <br>
    <input type=text required id="question" name="question">
    <br>
    
    <label for="date">Date:</label>
    <br>
    <input
        type="date"
        id="date"
        name="date"
        required
    />
    <br>

    <label for="time">Time:</label>
    <br>
    <input
        type="time"
        id="time"
        name="time"
        required
    />
    <br>

    <input type="submit" value="Add" id="submitFormButton">
    <div class="spinner"></div>
</form>
