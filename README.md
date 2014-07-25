## MageSpec

[![Build Status](https://travis-ci.org/MageTest/MageSpec.png?branch=develop)](https://travis-ci.org/MageTest/MageSpec)

## Installation

### Prerequisites

MageSpec requires PHP 5.3.x or greater.

### Install using composer

First, add MageSpec to the list of dependencies inside your `composer.json` and be sure to register few paths for autoloading:

```json
{
    "require-dev": {
        "magetest/magento-phpspec-extension": "~2.0"
    },
    "config": {
        "bin-dir": "bin"
    },
    "autoload": {
        "psr-0": {
            "": [
                "public/app",
                "public/app/code/local",
                "public/app/code/community",
                "public/app/code/core",
                "public/lib"
            ]
        }
    },
    "minimum-stability": "dev"
}
```

Then simply install it with composer:

```bash
$ composer install
```

You can read more about Composer on its [official webpage](http://getcomposer.org).

## SpecBDD and TDD

There isn’t any real difference between SpecBDD and TDD. The value of using a xSpec tool instead a regular xUnit tool for TDD is language. The concepts and features of the tool will keep your focus on the “right” things. The focus on verification and structure as opposed to behaviour and design is, of course, a valid one. We happen to find that the latter is more valuable on the long run. It was also the intention of early users of TDD.

## SpecBDD and StoryBDD

While with StoryBDD tools like [Behat](http://behat.org/) are used to understand and clarify the domain - specifying feature narratives, its need, and what do we mean by them - with SpecBDD we are only focused on the how: the implementation. You are specifying how your classes will achieve those features.

A good StoryBDD tool will let the business talk the domain language and drive the development by putting the focus on what really matters first.

Once you know why you are adding a feature and what it will be, it’s almost time to write code. But not yet! Adding code without a way to validate that it serves the specs just means you will have to go back and rework it, so that it does match the spec. And the later you find out you missed the requirement or added a bug, the harder and more expensive it is to fix. Kent Beck also adds that describing the code before you actually write it is a fear management technique. You don’t have to write all the code, just the spec of the next thing you want to work on. That executable spec will then guide you to what code you need to write. Once you do that, then what you have is a chance to refactor. Because if you change the behaviour of your code the specs will go red. So you spec so that you can refactor, or allow the design of your code to emerge in a sustainable way. SpecBDD tools are designed to guide you in the process, or at least not stand on the way.

It’s valid to assume that StoryBDD and SpecBDD used together are a very effective way to achieve highly customer-focused software.

## Basic usage

MageSpec has been developed as PhpSpec extension which means that it depends on it and that we need to tell PhpSpec to load the extension for us. In order to do that we have to create a file in our project root called phpspec.yml and add the following content to it:

```yml
extensions: [MageTest\PhpSpec\MagentoExtension\Extension]
```

However that's not enough. Due to the unusual and non-standard convention used by Magento to store controllers, models, helper and so on, MageSpec implement a custom PhpSpec locator service. Such a locator has to be properly configured accordingly to our project setup which means we need also to add some 'mage_locator' configuration as following:

```yml
extensions: [MageTest\PhpSpec\MagentoExtension\Extension]
mage_locator:
  spec_prefix: 'spec'
  src_path: 'public/app/code'
  spec_path: 'spec/public/app/code'
  code_pool: 'community'
```

Currently the mage_locator supports four options:

- namespace (default ''): The base namespace for our soruce code
- spec_prefix (default 'spec'): The namespace prefix which will be used to namespace your specs based on your source code namespace
- src_path (default 'src'): The relative path of your source code
- spec_path (default '.'): The relative path of your specs
- code_pool (default 'local'): Specifies the Magento code pool for creating the extension files. Options are 'local' and 'community'

### Describing a model

Say we are building a module that tells us if a product has existing reviews. We will work on simple things first and a design will emerge that will reach all the necessary features. Even though I have all the specs from the customer (we have done all our Behat feature files nicely), I know I will discover new things I will need, as soon as I sit down to write my classes.

What is the simplest thing I want to add? It should tell me if a product as a review.

So let’s do this. Well, not the boring bits. Let MageSpec take care of the boring stuff for us. We just need to tell MageSpec we will be working on the Review module's product class. So running the following command:

```bash
$ bin/phpspec describe:model 'magespec_reviews/product'
```

Should give us the following output

```bash
Specification for MageSpec_Reviews_Model_Product created in [...]/spec/public/app/code/local/MageSpec/Reviews/Model/ProductSpec.php
```

Ok. What have we just done? MageSpec has created the spec for us following the standard Magento convention. You can navigate to the spec folder and see the spec there:

```php
<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MageSpec_Reviews_Model_ProductSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MageSpec_Reviews_Model_Product');
    }
}
```

That’s awesome! MageSpec created the spec for me.

But first, let’s see what we have here. Our spec extends the special ObjectBehavior class. This class is special, because it gives you ability to call all the methods of the class you are describing and match the result of the operations against your expectations.

### Examples

The object behavior is made of examples. Examples are encased in public methods, started with it_. or its_. phpspec searches for such methods in your specification to run. Why underscores for example names? just_because_its_much_easier_to_read than someLongCamelCasingLikeThat.

### Matchers

Matchers are much like assertions in xUnit, except the fact that matchers concentrate on telling how the object should behave instead of verifying how it works. It just expresses better the focus on behaviour and fits better in the test-first cycle. There are 5 matchers in phpspec currently, but almost each one of them has aliases to make your examples read more fluid:

- Identity (return, be, equal, beEqualTo) - it’s like checking **===**
- Comparison (beLike) - it’s like checking **==**
- Throw (throw -> during) - for testing exceptions
- Type (beAnInstanceOf, returnAnInstanceOf, haveType) - checks object type
- ObjectState (have**) - checks object is** method return value

How do you use those? You’re just prefixing them with **should** or **shouldNot** depending on what you expect and call them on subject of interest.

Now we are ready to move on. Let’s update that first example to express my next intention:

```php
<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MageSpec_Reviews_Model_ProductSpec extends ObjectBehavior
{
    // ...

    function it_tells_if_a_product_has_reviews()
    {
        $this->load('SKU');
        $this->hasReviews()->shouldReturn(true);
    }

    // ...
}
```

Now what? We run the specs. You may not believe this, but MageSpec will understand we are describing a class that doesn’t exist and offer to create it!

```bash
$ bin/phpspec

MageSpec_Reviews_Model_Product
  10  ! it is initializable
      class MageSpec_Reviews_Model_Product does not exists.

-----------------------100%----------------------- 1

1 example (1 broken)
11ms

Do you want me to create `MageSpec_Reviews_Model_Product` for you? [Y/n]
Model MageSpec_Reviews_Model_Product created in [...]/public/app/code/local/MageSpec/Reviews/Model/Product.php.
```

MageSpec has now placed the empty class in the directory.

```php
<?php

class MageSpec_Reviews_Model_Product extends Mage_Core_Model_Abstract
{

}
```


You run your spec again and... OK, you guessed:

```bash
$ bin/phpspec
MageSpec_Reviews_Model_Product
  15  ! it tells if a product has reviews
      method MageSpec_Reviews_Model_Product::load() not found.

-----------------------97%----------------------- 2

2 examples (1 passed, 1 broken)
12ms


Do you want me to create `MageSpec_Reviews_Model_Product::load()` for you? [Y/n]
Method MageSpec_Reviews_Model_Product::load() has been created.
```

And again...

```bash
$ bin/phpspec
MageSpec_Reviews_Model_Product
  15  ! it tells if a product has reviews
      method MageSpec_Reviews_Model_Product::hasReviews() not found.

-----------------------97%----------------------- 2

@ examples (1 passed, 1 broken)
12ms


Do you want me to create `MageSpec_Reviews_Model_Product::hasReviews()` for you? [Y/n]
Method MageSpec_Reviews_Model_Product::hasReviews() has been created
```

What we just did was moving fast through the ambar state into the red. If you check your class you should now see something like this:

```php
<?php

class MageSpec_Reviews_Model_Product extends Mage_Core_Model_Abstract
{
    public function load($argument1)
    {
        // TODO: write logic here
    }

    public function hasReviews()
    {
        // TODO: write logic here
    }
}
```

We got rid of the fatal errors and ugly messages that resulted from nonnexistent classes and methods and went straight into a real failed spec:

```bash
$ bin/phpspec
MageSpec_Reviews_Model_Product
  15  ✘ it tells if a product has reviews
      expected true, but got null.

-----------------------97%----------------------- 2

2 examples (1 passed, 1 failed)
11ms
```

According to the TDD rules we now have full permission to write code. Red means “time to add code”; red is great! Now we add just enough code to make the spec green, quickly. There will be time to get it right, but first just get it green.

```php
<?php

class MageSpec_Reviews_Model_Product
{

    public function load($argument1)
    {
        // TODO: write logic here
    }

    public function hasReviews()
    {
        return true;
    }
}
```

And voilà:

```bash
$ bin/phpspec
----------------------100%----------------------- 2

2 example (2 passed)
11ms
```

If you are interested in know more about spec in PHP you better have a look to the [official PhpSpec page](http://phpspec.net/) or, if you are more in general interested in the whole TDD/SpecBDD cycle there are heaps of resources out there already. Here are just a couple for you look at:

- The [Rspec Book](http://www.amazon.com/RSpec-Book-Behaviour-Development-Cucumber/dp/1934356379) Development with RSpec, Cucumber, and Friends by David Chelimsky, Dave Astels, Zach Dennis, Aslak Hellesøy, Bryan Helmkamp, Dan North
- [Test Driven Development: By Example](http://www.amazon.com/Test-Driven-Development-Kent-Beck/dp/0321146530) by Kent Beck

## Additional supported commands

As per today, MageSpec currently allows you to describe different Magento classes. Following you can find a brief list.

### Describing a resource model

```bash
$ bin/phpspec describe:resource_model 'vendorname_modulename/resourcename'
```

### Describing a block

```bash
$ bin/phpspec describe:block 'vendorname_modulename/blockname'
```

### Describing a helper

```bash
$ bin/phpspec describe:helper 'vendorname_modulename/helpername'
```

## Issue Submission

Make sure you've read the [issue submission guidelines](https://github.com/MageTest/MageSpec/blob/develop/contributing.md#issue-submission) before you open a [new issue](https://github.com/MageTest/MageSpec/blob/develop/issues/new).

## Contribute

See the [contributing docs](https://github.com/MageTest/MageSpec/blob/develop/contributing.md)

# License and Authors

Authors: <https://github.com/MageTest/MageSpec/contributors>

Copyright (C) 2012-2013

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
