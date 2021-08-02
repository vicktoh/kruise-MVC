export type Data = {
  id: number | string;
  title: string;
  description: string;
  location_id: number;
  category_id: number;
  file_type: string;
  url: string;
  downloads: string;
  source: string;
  source_url: string;
  tags: [];
};

export type DatatableSearchOption = {
  title?: string;
};

export type DataRequestType = {
  perpage?: number;
  page: number;
  options: DatatableSearchOption;
};
