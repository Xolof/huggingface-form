<form action="/login" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <label for="email">Email</label><br>
    <input type=email required id="email" name="email"><br>
    <label for="password">Password</label><br>
    <input type=password required id="password" name="password"><br>
    <input type="submit" value="Login" id="submitFormButton">
    <div class="spinner"></div>
</form>
