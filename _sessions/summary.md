# Bird Island fixes: session summary

17 October 2026, working with Claude Code on the `html-port` branch. The changes were committed as `0e6a312` ("Went through site with Claude Code fixing errors"). The full conversation is in [transcript.md](transcript.md).

## Where things stood

Bird Island is Janice Kerbel's brochure site (2000–2002) for fictitious villas on an imaginary island in the Exumas. It's a hand-built site of about 30 HTML pages from 2000: table layouts, sliced GIF headings and menus, `<font>` tags, and JavaScript popup windows for the beach photos, floorplans, site plan, calendar and map close-up. The registration form's PHP processing had already been removed on the `html-port` branch (`1c9951c`), so the form now just goes to `thanks.html`.

The aim of this session was to find errors and things that had broken over the years, and to make the site reasonably accessible and future-proof without changing how the artwork looks.

## How the changes were checked

Every page was screenshotted in headless Chrome (1024px wide) before any change, and again afterwards, and the two sets were compared pixel by pixel with ImageMagick. Every page except `index.html` came out byte-identical. `index.html` moved by a few pixels because its table attributes now apply (see below); a side-by-side comparison showed no visible difference. A link checker was also run over all `href`, `src` and `background` references before and after.

## Bugs fixed

- **Beach map pointing at the wrong pages** (`island/island_pictures.html`). The "perico beach" hotspot opened `solitaire.html` and the "solitude beach" hotspot opened `perico.html`. Both now open the matching page. The draft map in `img/island_view.html` agrees with the hotspot labels, so it was the targets that were wrong. One hotspot also had `HREF` twice.
- **`index.html`**: a missing quote in `height="100%` swallowed the table's `cellpadding`, `cellspacing` and `border` attributes. A space was missing between two attributes, and the link was closed inside a `<div>` it had opened outside.
- **`welcome.html`**: the first row of the image grid had no `<tr>`.
- **`birdisland.css`**: it was wrapped in `<style>` tags, which aren't valid in an external stylesheet, so browsers dropped the `body` rule. It also used a non-existent `bgcolor` property and the font name `sans serif` (now `sans-serif`).
- **`invest/calendar.html`**: missing `</HEAD>`, and two attributes run together.

## Popup windows

All nine links that open popups were `href="#"` with an `onClick` handler, so with JavaScript off, or with middle-click or "open in new tab", they went nowhere. They now link to the real page and still open it as a popup when JavaScript is on:

```html
<a href="siteplan.html" onclick="window.open(this.href, 'siteplan', 'width=650,height=500,resizable,scrollbars'); return false;">
```

The "close window" link in each popup closes the window if it was opened as a popup, and otherwise goes back to the page that links to it. The misspelt window feature `resizeable` is now `resizable`, and stray `';` characters after the handlers were removed.

## Accessibility

- Every page has `lang="en"` and `<meta charset="utf-8">`. All the files are plain ASCII, so there was no risk of characters changing.
- The 205 `trans.gif` spacers had no `alt`, and the empty grid boxes had `alt="Empty square"` (82 of them) or `alt="the island"` (3). They all now have `alt=""`, so screen readers skip them.
- The Exumas map hotspot has alt text. The aerial photo's alt text no longer says "Arial", and it explains that the beaches can be selected.
- Registration form: every field has a `<label>`; the yes/no radio buttons and the 52 week checkboxes are grouped under their questions; the email field is `type="email"`; and name, country and email have `autocomplete` values.

## Privacy

With the processing removed, the form sent everything a visitor typed (name, email, country, income and so on) to `thanks.html` as a query string, which ends up in server access logs. The free-text fields, drop-downs and checkboxes no longer have `name` attributes, so they aren't submitted. Only the yes/no radio answers are, since radio buttons need a name to work as a group. The required fields are still checked by the browser before the thanks page is shown.

## Removed

These are all still in git history.

- The four `ws_ftp.log` files, which recorded the original FTP uploads and server paths from 2000.
- `css/style.css`, `css/tabs.css` and `css/winxp.blue.css`. Nothing referenced them, and they pointed to about 30 images that don't exist. They looked like leftovers from a form-processing package.
- `oops.html` and `oopsup.html`, the error pages the old `processForm.php` redirected to.

## Left as it is, on purpose

- **Quirks mode.** The pages keep their original HTML 3.2 / 4.0 doctypes (or none), so browsers render them in quirks mode. Switching to `<!DOCTYPE html>` would put them in standards mode, where an image alone in a table cell sits on a full text line. The 1–6px spacer images would grow, opening gaps in the sliced layout. Quirks mode is defined in the HTML standard and browsers must keep supporting it, so it's the safer way to preserve the work. `<font>`, `bgcolor`, `align` and the `marginwidth`-style body attributes are also still defined in the standard.
- **The design itself.** The tiny `size="1"` text, headings and menu items drawn as images, and the fixed 760px width are the weakest points for accessibility, but changing them would change the artwork. No viewport meta tag was added, because phones display a fixed-width page like this better when they zoom out to fit it.
- **Unfinished drafts.** `island/ibis.html`, `island/vireo.html` and `island/solitaire.html` aren't linked from anywhere and reuse other beaches' photos. `img/island_view.html` is an earlier version of the beach map with five hotspots. About 60 images in `img/` are unused, including one with a space in its name (`land survey.gif`). These were kept for the archive.
- `robots.txt` and `.htaccess` are empty files, which does no harm.
