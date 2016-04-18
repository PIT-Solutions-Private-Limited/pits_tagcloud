.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _typoscript:

TypoScript Setup
================

In the TypoScript configurations setup we can specify the links and link parameters for specific tables.
Also you can exclude certain uids from getting tagged.

.. _example:

Example
"""""""""
.. container:: table-data

   Example
         ::

            plugin.tx_pitstagcloud_tagcloud {
               settings {
                   tabletags{
                         pages{
                            links.parameter = uid
                            excludeRecords = 1,5
                            andWhere = doktype = 1
                         }
                         tt_news{
                            links.parameter = {$plugin.tt_news.singlePid}
                            links.additionalParams = tx_ttnews[tt_news]
                            links.attributeValue = uid
                            excludeRecords =
                            andWhere =
                         }
                     }
                }
           }


plugin.tx\_pitstagcloud\_tagcloud.settings.tabletags
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. _tablename:

tablename
"""""""""

.. container:: table-row

   Property
         tablename

   Data type
         string

   Description
         Define the name of the table for which the link have to be generated.


plugin.tx\_pitstagcloud\_tagcloud.settings.tabletags.[tablename].links
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. _parameter:

parameter
"""""""""

.. container:: table-row

   Property
         parameter

   Data type
         string

   Description
         The page id or the field to which the link has to point to.

   Default
          The default value is set to 'pid'

   Example
         For tt_news singlePid is set as:  links.parameter = {$plugin.tt_news.singlePid}




.. _additionalParams:

additionalParams
"""""""""""""""""

.. container:: table-row

   Property
         additionalParams

   Data type
         string

   Description
         If there are additional parameters to be passed to the URL. Specify the extension key along with the attribute name.

   Example
         For tt_news single page view the value is set as:  additionalParams = tx_ttnews[tt_news]


.. _attributeValue:

attributeValue
"""""""""""""""

.. container:: table-row

   Property
         attributeValue

   Data type
         string

   Description
         The value of the parameter/attribute to be paased in the URL string.

   Example
          For tt_news single page view for the additional parameter tx_ttnews[tt_news], we give the value for the attribute property as 'uid'. attributeValue = uid



plugin.tx\_pitstagcloud\_tagcloud.settings.tabletags.[tablename].excludeRecords
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. _excludeRecords:

excludeRecords
""""""""""""""

.. container:: table-row

   Property
         excludeRecords

   Data type
         string

   Description
         comma seperated list of uids to be excluded from tagging

   Default
          Null


plugin.tx\_pitstagcloud\_tagcloud.settings.tabletags.[tablename].andWhere
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. _andWhere:

andWhere
""""""""""""""

.. container:: table-row

   Property
         andWhere

   Data type
         string

   Description
         The extended where condition which you want to add to the query while fetching tags. You can define it specifically for each table.

   Default
          Null

   Example
          For pages table we can extend the query to consider only those pages with doktype = 1.

          pages.andWhere = doktype = 1