# TYPO3 Extension `md_news_clickcount`
With this extension you are able, to count views/clicks of `ext:news`-records. Additionally it integrates a new option
in the plugin of `ext:news` to show records ordered by views. On top there is a scheduler task for automatically
reset all counts.

## Requirements
- TYPO3 12.4 || 13.4
- ext:news >= 11.0

## Installation
- Install the extension by using the extension manager or use composer (`composer req mediadreams/md_news_clickcount`)
- Include the static TypoScript of the extension

## Usage

### Count views
- In the news detail view template add the following code:<br>
`{md:getCountImg(newsUid: '{newsItem.uid}')}`<br>
Make sure, that you import the namespace to the ViewHelper by adding the following line at the top of the template:
`{namespace md=Mediadreams\MdNewsClickcount\ViewHelpers}`

Note:

- All hits to a news preview have the speciel parameter `tx_news_pi1%5Bnews_preview%5D`. When this is set, not tracking happens.
- By default, all hits of logged in backend users will be ignored. Enable visits of logged in backend users in the extension settings, if needed.

### Show most read news
- Insert the plugin `News system` of `ext:news` on a page
- In `Settings` tab select `List view (without overloading detail view)` in the `What to display` dropdown
- Select `Views` in the `Sort by` dropdown
- Select a `Sort direction` (`Descending` will show the most viewed articles first)
- Save and close

### Spam prevention
In the extension settings, you are able to set `daysForNextCount`, which adds an IP check. So multiple views of the same
IP address in the given timespan (days) will be counted just once.

By default this functionality is disabled (`daysForNextCount = 0`)

ATTENTION:

If you have set `daysForNextCount` to something higher than 0, please make sure, that you activate the scheduler task
`mdNewsClickcount:cleanupLogCommand` of type `Execute console commands` in order to meet the GDPR requirements! This
will clean up the log.

### Clear views
- Add a scheduler task of type `Execute console commands`
- Select `mdNewsClickcount:clearViewsCommand` in `Schedulable Command` dropdown

## Bugs and Known Issues
If you find a bug, it would be nice if you add an issue on [Github](https://github.com/cdaecke/md_news_clickcount/issues).

# THANKS

Thanks a lot to all who make this outstanding TYPO3 project possible!

## Credits

- Extension icon was copied from [ext:news](https://github.com/georgringer/news) and enriched with a pen from [Font Awesome](https://fontawesome.com/icons/hand-pointer?style=solid).
