export default [
  {
    header: 'Job Order',
  },
  {
    title: 'Create',
    route: 'create-jo',
    icon: 'FilePlusIcon',
  },
  {
    title: 'All JO',
    route: 'all-jo',
    icon: 'ListIcon',
  },
 
  {
    header: 'Scheduling',
  },
  {
    title: 'Calendar',
    route: 'calendar',
    icon: 'CalendarIcon',
  },
  {
    title: 'Reports',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'Report 1',
        route: null,
      },
      {
        title: 'Report 2',
        route: null,
      },
    ],
  },

]
