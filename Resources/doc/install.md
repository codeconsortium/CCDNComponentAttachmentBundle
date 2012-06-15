Installing CCDNComponent AttachmentBundle 1.0
=============================================

## Dependencies:

1. [PagerFanta](https://github.com/whiteoctober/Pagerfanta).
2. [PagerFantaBundle](http://github.com/whiteoctober/WhiteOctoberPagerfantaBundle).
3. [CCDNComponent CommonBundle](https://github.com/codeconsortium/CommonBundle).
4. [CCDNComponent CrumbTrailBundle](https://github.com/codeconsortium/CrumbTrailBundle).
5. [CCDNComponent DashboardBundle](https://github.com/codeconsortium/DashboardBundle).

## Installation:

Installation takes only 9 steps:

1. Download and install the dependencies.
2. Register bundles with autoload.php.
3. Register bundles with AppKernel.php.  
4. Run vendors install script.
5. Update your app/config/routing.yml. 
6. Update your app/config/config.yml. 
7. Update your database schema.
8. Symlink assets to your public web directory.
9. Warmup the cache.
    
### Step 1: Download and install the dependencies.
   
Append the following to end of your deps file (found in the root of your Symfony2 installation):

``` ini
[CCDNComponentAttachmentBundle]
    git=http://github.com/codeconsortium/AttachmentBundle.git
    target=/bundles/CCDNComponent/AttachmentBundle

```

### Step 2: Register bundles with autoload.php.

Add the following to the registerNamespaces array in the method by appending it to the end of the array.

``` php
// app/autoload.php
$loader->registerNamespaces(array(
    'CCDNComponent'    => __DIR__.'/../vendor/bundles',
	**...**
));
```

### Step 3: Register bundles with AppKernel.php.

In your AppKernel.php add the following bundles to the registerBundles method array:  

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
		new CCDNComponent\AttachmentBundle\CCDNComponentAttachmentBundle(),
		**...**
	);
}
```

### Step 4: Run vendors install script.

From your projects root Symfony directory on the command line run:

``` bash
$ php bin/vendors install
```

### Step 5: Update your app/config/routing.yml. 

In your app/config/routing.yml add:  

``` yml
CCDNComponentAttachmentBundle:
    resource: "@CCDNComponentAttachmentBundle/Resources/config/routing.yml"
    prefix: /

```

### Step 6: Update your app/config/config.yml. 

In your app/config/config.yml add:    

``` yml
#
# for CCDNComponent AttachmentBundle
#
ccdn_component_attachment:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
    store:
        dir: %ccdncomponent_attachmentbundle_file_store%
    quota_per_user:
        max_files_quantity: 20
        max_filesize_per_file: 400KiB
        max_total_quota: 2000KiB

```

and in your app/config/parameters.ini add and set the value to the directory where you want to keep your attachment files:

``` ini
ccdncomponent_attachmentbundle_file_store= "/your/folder/where/you/want/to/store/attachments"
```
**Warning:**

>Set the appropriate layout templates you want under the sections 'layout_templates' and the 
route to a users profile if you are not using the [CCDNUser\ProfileBundle](http://github.com/codeconsortium/CCDNUserProfileBundle). Otherwise use defaults.

### Step 7: Update your database schema.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

### Step 8: Symlink assets to your public web directory.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console assets:install --symlink web/
```

### Step 9: Warmup the cache.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console cache:warmup
```

## Next Steps.

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
