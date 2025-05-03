<form action="/" method="GET">
    <label for="question">Say something to the LLM:</label><br>
    <textarea
        required id="question"
        name="question"
        rows="4"
        cols="50"
    ><?= isset($question) ? strip_tags($question) : null ?></textarea><br>
    <input type="submit" value="Go" id="submitFormButton">
    <div class="spinner"></div>
</form>
