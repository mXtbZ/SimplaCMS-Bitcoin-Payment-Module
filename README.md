# SimplaCMS-Bitcoin-Payment-Module
Payment module for SimplaCMS, easy to adapt to any other platfom

1. Register at Block.io

2. Get from them an API KEY for BTC

If using any other platform rather than SimplaCMS:

1. In payment/dir/config.php and payment/dir/BTCPay.php and in /BTCPay_cron.php check variables (compare to settings.xml settings)

2. See payment/dir/callback.php how to adapt this script to your platform


Do not forget to add cron job for /BTCPay_cron.php

That's all !
