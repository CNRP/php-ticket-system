<section class="navigation">
    <ul>
        <li><p>PHP Support Ticket System</p></li>
        <li><a href="/index.php" class="button">Submit a ticket</a></li>
    </ul>
    <div class="right">
        <!-- <form action="/search.php">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form> -->
        <?php if(isset($_SESSION['user'])){?>
            <a href="/account.php" id="cart-toggle" class="button" onclick="toggleNav()">
                Account <i class="fa-solid fa-user"></i>
            </a>
        <?php }else {?>
            <a href="/auth/login.php" class="button">
                Login <i class="fa-solid fa-user"></i>
            </a>
        <?php }?>
    </div>
</section>
