<?php

namespace App\Enums;

enum SeoSchemaType: string
{
    case WebPage = 'WebPage';
    case Article = 'Article';
    case Report = 'Report';
    case ScholarlyArticle = 'ScholarlyArticle';
    case Person = 'Person';
    case Organization = 'Organization';
    case WebSite = 'WebSite';
}
