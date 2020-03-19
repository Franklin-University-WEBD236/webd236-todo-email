%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <p>Enter your email address and we will mail you a link you can use to reset your
    password.</p>
    <form action="@@user/reset@@" method="post">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter email address" value="{{value($form['email'])}}" />
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="return get('@@index@@')">Cancel</button>
      </div>
    </form>
  </div>
</div>

%% views/footer.html %%
