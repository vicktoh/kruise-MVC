export type Auth = {
    userId?: string;
    email?: string;
}
export type HTTPResponseType <T> =  {
    message?:string;
    data?: T;
    status?: string;
  }