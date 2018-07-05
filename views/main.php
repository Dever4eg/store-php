
<div class="row">
    <div class="col-md-12">


        <?php foreach ($products as $key => $item): ?>

        <?php if($key%3 === 0):?>
            <div class="card-deck">
        <?php endif; ?>

            <div class="card">
                <img class="card-img-top " src="https://cnet2.cbsistatic.com/img/hjo7_UY6ykIh_9cfttUZYaiF7V0=/770x433/2017/06/21/39c814c4-4909-43e8-aa0d-89eff3aced37/apple-macbook-12-inch-2017-01.jpg">
                <div class="card-body">
                    <h5 class="card-title"> <?php echo $item['name'] ?> </h5>
                    <p><?php echo $item['description'] ?></p>
                </div>
                <div class="card-footer">
                    <a href="details?id=<?php echo $key ?>" class="btn btn-default">Details</a>
                    <a href="#" class="btn btn-primary float-right">Buy</a>
                </div>
            </div>

        <?php if($key%3 === 2):?>
            </div>
        <?php endif; ?>

        <?php endforeach; ?>

    </div>
</div>
