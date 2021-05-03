# TYPO3 Extension `md_news_clickcount`
With this extension you are able, to count views of `ext:news`-records.

It integrates a new option in the plugin of `ext:news` to count all views of a news record and a configuration to show records ordered by views. On top
there is a scheduler task for automatically reset all counts.

## Requirements
- TYPO3 >= 10.4
- ext:news >= 8.0

## Installation
- Install the extension by using the extension manager or use composer
- Include the static TypoScript of the extension

## Usage

### Count views
There are two ways of counting views:

#### 1. News plugin
- Insert the plugin `News system` of `ext:news` on the news detail page
- In `Settings` tab select `Count views` in the `What to display` dropdown
- Save and close

#### 2. TypoScript
- Include `<f:cObject typoscriptObjectPath="lib.newsIncreaseCount"/>` in the news detail template

### Show most read news
- Insert the plugin `News system` of `ext:news` on a page
- In `Settings` tab select `List view (without overloading detail view)` in the `What to display` dropdown
- Select `Views` in the `Sort by` dropdown
- Select a `Sort direction` (`Descending` will show the most viewed articles first)
- Save and close

### Clear views
- Add a scheduler task of type `Execute console commands`
- Select `mdNewsClickcount:clearViewsCommand` in `Schedulable Command` dropdown

## Bugs and Known Issues
If you find a bug, it would be nice if you add an issue on [Github](https://github.com/cdaecke/md_news_clickcount/issues).

# THANKS

Thanks a lot to all who make this outstanding TYPO3 project possible!

## Credits

- Extension icon was copied from [ext:news](https://github.com/georgringer/news) and enriched with a pen from [Font Awesome](https://fontawesome.com/icons/hand-pointer?style=solid).
