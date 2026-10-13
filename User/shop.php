<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include 'header.php';
  ?>
  <section class="shop-section py-5">
    <div class="container">
      <h2 class="section-title text-center mb-4">Our Pet Products</h2>
      
      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="product-item">
            <img src="images/item1.jpg" alt="Pet Product" class="img-fluid">
            <h3>Dog Food</h3>
            <p>Premium nutrition for your furry friend</p>
            <div class="price">$25.99</div>
            <button class="btn btn-primary">Add to Cart</button>
          </div>
        </div>
        <!-- Repeat for more products -->
      </div>

      <div class="pagination justify-content-center mt-4">
        <nav aria-label="Product navigation">
          <ul class="pagination">
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </section>
<?php
  include 'footer.php';
?>
 </body>
</html>