# Microseconds DateTime Support for Doctrine


## What is this about?
This library has no fancy logo. It also lacks all cool badges, but what it has is a simple & ready-to-use implementation
of *mili-* and *microsecond* types for Doctrine ORM/DBAL.


## Why?
Date and time is hard, databases are hard - a combination of the two is a nightmare. There's a [5+ years old issue](https://github.com/doctrine/dbal/issues/2873)
describing the problem. To do it properly and across all platforms seems nearly impossible. However, it is possible to
do it with a limited scope. This is why this package was created: [I](https://github.com/kiler129)
[personally](https://github.com/doctrine/dbal/issues/2873#issuecomment-476294822) stepped into that issue many times
over the years, and here came the time to stop copying & pasting the same code.


## How to use?
1. **Install with composer**: `composer require owlcorp/doctrine-microseconds-datetime` (it will work across PHP7.0-8+)
2. **Add DBAL types**
  - If you're using Symfony, edit `config/packages/doctrine.yaml` and add:
    ```yaml
    doctrine:
        dbal:
            types:
                time_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\TimeMicroType
                time_immutable_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\TimeImmutableMicroType
                datetime_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\DateTimeMicroType
                datetime_immutable_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\DateTimeImmutableMicroType
                datetimetz_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\DateTimeTzMicroType
                datetimetz_immutable_micro: OwlCorp\DoctrineMicrotime\DBAL\Types\DateTimeTzImmutableMicroType
    ```
  - If you're not using Symfony check official [Doctrine documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/cookbook/custom-mapping-types.html).
3. **For ORM**, you can use it like so:

    ```php
    <?php declare(strict_types=1);
    
    use Doctrine\ORM\Mapping as ORM;
    
    #[ORM\Entity]
    class MicroEntity
    {
        #[ORM\Column(type: 'time_micro')] //you can use text names
        public \DateTime $time;
    
        #[ORM\Column(type: TimeImmutableMicroType::NAME)] //or constants
        public \DateTimeImmutable $timeImmutable;
    
        /**
         * @ORM\Column(type="datetime_micro") Of course, it works with annotations too
         */
        public \DateTime $dateTime;
    }
    ```


## What's supported?
See table below. These are combos which I was able to test, and they should cover most of the usecases. If you know
about another database engine supporting it and it can be confirmed easily issues are welcome :)

|              ↓ DB ↓ \| Type  →  | `time_micro` | `datetime_micro` | `datetimetz_micro`<sup>3</sup> |
|:-------------------------------:|--------------|------------------|-------------------------------|
| **PostgreSQL <10**              |       ✅      |   ✅<sup>2</sup>  |    ✅<sup>2</sup>          |
| **PostgreSQL 10+**              |       ✅      |         ✅        |          ✅                |
| **MySQL 5.6.4+**                |       ✅      |         ✅        |          ❌                |
| **Oracle-Xe**                   |       ✅      |         ✅        |          ✅                |
| **Microsoft SQL <2008**         |       ⚠️      |         ⚠️        |          ❌                |
| **Microsoft SQL 2008+ & Azure** |       ✅      |         ✅        |          ✅                |
| **SQLite 3**<sup>1</sup>        |       ⚠️      |         ⚠️        |          ❌                |
| _Other databases_               |       ❔      |         ❔        |          ❌                |

✅ = full microseconds support (`.000000`) | ⚠️ = miliseconds only (`.000`) | ❌ = not supported

***Quirks***
1. *SQLite does't support native `TIME`/`DATETIME` fields, but internal functions support text-based representation with milisecond precision.*
2. *Older PgSQL in certain edge-cases could loose some precision, you're unlikely to hit the [non-Y2K year-2000 bug](https://www.postgresql.org/docs/9.6/datatype-datetime.html).*
3. *Bonus: yes, timezone support is broken in most databases. Even where supported you probably [shouldn't use it](https://derickrethans.nl/storing-date-time-in-database.html).*


*Sources*
- https://github.com/doctrine/dbal/issues/2873
- https://www.postgresql.org/docs/10/datatype-datetime.html
- https://dev.mysql.com/doc/refman/5.6/en/date-and-time-type-syntax.html
- http://helpdoco.com/Oracle/time-in-microseconds.htm
- https://learn.microsoft.com/en-us/sql/t-sql/data-types/datetime-transact-sql

