/*

NOTE: tag is not supported in horizontal menu

Array of object

Top level object can be:
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- nav Grp (top level grp is called header group) ---/

title
icon (if it's on top/second level)
children

/--- nav Item (top level item is called header link) ---/

icon (if it's on top/second level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)

*/
/*async function getData() {
    const url = "/api/menu";
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      }
  
      const json = await response.json();
      console.log(json);
    } catch (error) {
      console.error(error.message);
    }
}
console.log(getData());
*/
/*import { rest } from 'msw'
const handlerNavItems = [
    rest.get(('/api/menu'), (req, res, ctx) => {
      return res(
        ctx.status(200),
        ctx.json({ navItems: db.navItems}),
      )
    }),
]
console.log(handlerNavItems);
*/
/*async function fetchMenu() {
    const response = await fetch('/api/menu');
    const menu = await response.json();
    return menu;
}
const menus = []
fetchMenu().then(menu => {
    menus.push(menu)
});
console.log(menus);
*/
//const colors = await fetch("/api/menu").then((response) => response.json()).then( hydrateSomehow );
//export default colors;
export const fetchMenu = async() => {
    const response = await fetch('/api/menu');
    const menu = await response.json();
    console.log(menu);
    
    return menu;
}
//console.log(fetchMenu());
export default fetchMenu()
/*import dashboard from './dashboard'
import apps from './apps'
import pages from './pages'
import chartsAndMaps from './charts-and-maps'
import uiElements from './ui-elements'
import formAndTable from './forms-and-table'
import others from './others'
console.log([...dashboard, ...apps, ...uiElements, ...formAndTable, ...pages, ...chartsAndMaps, ...others]);

// Array of sections
export default [...dashboard, ...apps, ...uiElements, ...formAndTable, ...pages, ...chartsAndMaps, ...others]
*/