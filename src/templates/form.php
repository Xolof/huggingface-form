<form action="/" method="GET">
    <label for="question">Say something to the LLM:</label><br>
    <textarea required id="question" name="question" rows="4" cols="50"><?= isset($question) ? htmlspecialchars($question) : null ?></textarea><br>
    <input type="submit" value="Submit" id="submitFormButton">
    <div class="spinner"></div>
</form>
